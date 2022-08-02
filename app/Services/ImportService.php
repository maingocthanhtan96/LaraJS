<?php

namespace App\Services;

use App\Imports\DataRawImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportService extends BaseService
{
    public function import()
    {
        $import = new DataRawImport();
        $data   = head(Excel::toArray($import, request()->file('file')));
        $chunk  = collect($data)->chunk(1000);
        $i      = 0;

        foreach ($chunk as $data) {
            foreach ($data as $row) {
                //Skip Row Title
                if ($i++ <= 1) {
                    continue;
                }

                $unix_date = ($row[3] - 25569) * 86400;
                $row[3]    = date("Y-m-d", $unix_date);

                if ($row[3] == date("Y-m-d")) {
                    continue;
                }

                $temp_checkin  = date("H:i:00", strtotime($row[6]));
                $temp_checkout = date("H:i:00", strtotime($row[7]));
                if (strtotime($row[6]) < strtotime("8:30:00")) {
                    $temp_checkin = '8:30:00';
                }

                //Cho nợ 15 phút trễ
                if (strtotime($row[7]) > strtotime("17:45:00")) {
                    $temp_checkout = '17:45:00';
                }

                //Tính tổng giờ làm
                $total_hours     = $this->getTotalHours($temp_checkin, $temp_checkout, $row[7]);
                $work_hours_left = $this->getWorkHoursLeft($total_hours);

                //Kiểm tra checkin checkout có null hay không? nêú null tổng giờ = 0
                if ($row[7] == null or $row[6] == null or strtotime($row[7]) <= strtotime("8:30:00")) {
                    $total_hours     = 0;
                    $work_hours_left = 480;
                }

                if ($row[7] == null and $row[6] == null and $row[14] == null) {
                    continue;
                }

                //Kiểm tra có nhập vân tay khi về hay không
                if ($row[7] == 'N/A' or $row[7] == null) {
                    return ['no-finger-leave', $row[2], $row[3]];
                }

                $row[1] = str_replace(' ', '', $row[1]);

                //Tính giờ làm trên 8 tiếng
                $ot_hours = $this->getOT($temp_checkout);
                //Tính đi trễ
                $late = $this->getLate($temp_checkin);
                //Tính về sớm
                $leave_early = $this->getLeaveEarly($temp_checkout);

                //Kiểm tra dữ liệu có hay không -first
                if ($this->workTimeService->checkDatabyId($row[1]) != null) {
                    $updated = $this->workTimeService->checkUpdated($row[3], $row[1]);
                    if (!empty($updated)) {
                        if ($updated->updated_at != null) {
                            continue;
                        }
                    }
                    if ($exist = $this->workTimeService->checkExistDataImport($row[1], $row[3])) {
                        $update_data[$exist->id] = $this->updateData($row, $total_hours, $ot_hours, $late, $leave_early, $work_hours_left);
                    } else {
                        $insert_data[] = $this->insertData($row, $total_hours, $ot_hours, $late, $leave_early, $work_hours_left);
                    }
                } else {
                    $insert_data[] = $this->insertData($row, $total_hours, $ot_hours, $late, $leave_early, $work_hours_left);
                }
            }
        }

        if (!empty($insert_data)) {
            $this->insert($insert_data);
        }
        if (!empty($update_data)) {
            $this->update($update_data);
        }

        return;
    }
}
