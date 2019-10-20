'use strict';

module.exports = function () {
  return {
    framework: 'bulma',
    table: 'el-table el-table--fit el-table--striped el-table--border el-table--enable-row-hover el-table--enable-row-transition w-full',
    row: '',
    column: 'el-col el-col-24 flex-wrap mb-6',
    label: 'cell',
    input: 'el-input el-input__inner',
    select: 'el-select el-input__inner',
    field: 'flex',
    inline: 'flex',
    right: 'float-right',
    left: 'float-left',
    center: 'text-center',
    contentCenter: 'justify-center',
    icon: 'icon',
    small: 'el-input--small',
    nomargin: 'marginless',
    button: 'el-button',
    groupTr: 'selected',
    dropdown: {
      container: 'el-dropdown',
      trigger: 'dropdown-trigger',
      menu: 'el-dropdown-menu',
      content: 'dropdown-content',
      item: 'dropdown-item',
      caret: 'fa fa-angle-down',
    },
    pagination: {
      nav: '',
      count: '',
      wrapper: 'el-pagination',
      list: 'el-pager',
      item: '',
      link: 'number cursor-default font-medium',
      next: '',
      prev: '',
      active: 'active',
      disabled: 'cursor-not-allowed',
    },
  };
};
