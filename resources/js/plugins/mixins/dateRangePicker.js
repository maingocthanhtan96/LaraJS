export default {
  computed: {
    pickerOptions() {
      return {
        shortcuts: [
          {
            text: this.$t('date.today'),
            onClick(picker) {
              const start = new Date();
              picker.$emit('pick', [start, start]);
            }
          },
          {
            text: this.$t('date.yesterday'),
            onClick(picker) {
              const start = new Date();
              const end = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 - 86400);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: this.$t('date.this_week'),
            onClick(picker) {
              const now = new Date();
              const start = now.getDate() - now.getDay() + 1; // First day is the day of the month - the day of the week
              const end = start + 6; // last day is the first day + 6
              picker.$emit('pick', [
                new Date(now.setDate(start)),
                new Date(now.setDate(end))
              ]);
            }
          },
          {
            text: this.$t('date.last_week'),
            onClick(picker) {
              const now = new Date();
              const start = now.getDate() - now.getDay() - 7 + 1; // First day is the day of the month - the day of the week
              const end = now.getDate() - now.getDay(); // last day is the first day + 6
              picker.$emit('pick', [
                new Date(now.setDate(start)),
                new Date(now.setDate(end))
              ]);
            }
          },
          {
            text: this.$t('date.last_14_days'),
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 14);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: this.$t('date.last_30_days'),
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: this.$t('date.this_month'),
            onClick(picker) {
              const now = new Date();
              picker.$emit('pick', [
                new Date(now.getFullYear(), now.getMonth(), 1),
                new Date(now.getFullYear(), now.getMonth() + 1, 0)
              ]);
            }
          },
          {
            text: this.$t('date.last_month'),
            onClick(picker) {
              const now = new Date();
              picker.$emit('pick', [
                new Date(now.getFullYear(), now.getMonth() - 1, 1),
                new Date(now.getFullYear(), now.getMonth(), 0)
              ]);
            }
          },
          {
            text: this.$t('date.last_3_months'),
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: this.$t('date.last_6_months'),
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 180);
              picker.$emit('pick', [start, end]);
            }
          },
          {
            text: this.$t('date.this_year'),
            onClick(picker) {
              const now = new Date();
              picker.$emit('pick', [
                new Date(now.getFullYear(), 0, 1),
                new Date(now.getFullYear(), 12, 0)
              ]);
            }
          }
        ]
      };
    }
  }
};
