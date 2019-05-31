const bulma = require('./bulma')();

const _merge = require('merge');

const _merge2 = _interopRequireDefault(_merge);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

module.exports = function (h, modules, classes, slots) {
	let classesCs = bulma;
	const filterId = `VueTables__search_${this.id}`;
	const ddpId = `VueTables__dropdown-pagination_${this.id}`;
	const perpageId = `VueTables__limit_${this.id}`;
	const perpageValues = require('./modules/per-page-values').call(this, h);


	const genericFilter = this.hasGenericFilter ? h(
		'div',
		{ class: 'VueTables__search-field' },
		[h(
			'label',
			{
				attrs: { for: filterId },
				class: classesCs.label,
			},
			[this.display('filter')],
		), modules.normalFilter(classesCs, filterId)],
	) : '';

	const perpage = perpageValues.length > 1 ? h(
	  'div',
	  { class: 'VueTables__limit-field' },
	  [h(
	    'label',
	    { class: classesCs.label, attrs: { for: perpageId } },
	    [this.display('limit')],
	  ), modules.perPage(perpageValues, classesCs.select, perpageId)],
	) : '';

	const dropdownPagination = this.opts.pagination && this.opts.pagination.dropdown ? h(
	  'div',
	  { class: 'VueTables__pagination-wrapper' },
	  [h(
	    'div',
	    {
	      class: `${classesCs.field} ${classesCs.inline} ${classesCs.right} VueTables__dropdown-pagination`,
	      directives: [{
	        name: 'show',
	        value: this.totalPages > 1,
	      }],
	    },
	    [h(
	      'label',
	      {
	        attrs: { for: ddpId },
	      },
	      [this.display('page')],
	    ), modules.dropdownPagination(classesCs.select, ddpId)],
	  )],
	) : '';

	const columnsDropdown = this.opts.columnsDropdown ? h(
	  'div',
	  { class: 'VueTables__columns-dropdown-wrapper' },
	  [modules.columnsDropdown(classesCs)],
	) : '';

	// const perpage = '';
	// const dropdownPagination = '';
	// const columnsDropdown = '';

	const footerHeadings = this.opts.footerHeadings ? h('tfoot', [h('tr', [modules.headings(classesCs.right)])]) : '';

	const tableTop = genericFilter || perpage || dropdownPagination || columnsDropdown || slots.beforeFilter || slots.afterFilter || slots.beforeLimit || slots.afterLimit ? h(
		'div',
		{ class: classesCs.row },
		[h(
			'div',
			{ class: classesCs.column },
			[h(
				'div',
				{ class: `${classesCs.field} ${classesCs.inline} ${classesCs.left} VueTables__search` },
				[slots.beforeFilter, genericFilter, slots.afterFilter],
			), h(
				'div',
				{ class: `${classesCs.field} ${classesCs.inline} ${classesCs.right} VueTables__limit` },
				[slots.beforeLimit, perpage, slots.afterLimit],
			), dropdownPagination, columnsDropdown],
		)],
	) : '';

	return h(
		'div',
		{ class: `VueTables VueTables--${this.source}` },
		[tableTop, slots.beforeTable, h(
			'div',
			{ class: 'table-responsive' },
			[h(
				'table',
				{ class: `VueTables__table ${this.opts.skin ? this.opts.skin : classesCs.table}` },
				[h('thead', [h('tr', [modules.headings(classesCs.right)]), slots.beforeFilters, modules.columnFilters(classesCs), slots.afterFilters]), footerHeadings, slots.beforeBody, h('tbody', [slots.prependBody, modules.rows(classesCs), slots.appendBody]), slots.afterBody],
			)],
		), slots.afterTable, modules.pagination((0, _merge2.default)(classesCs.pagination, {
			wrapper: `${classesCs.row} ${classesCs.column} ${classesCs.contentCenter}`,
			nav: classesCs.center,
			count: `${classesCs.center} ${classesCs.column}`,
		})), modules.dropdownPaginationCount()],
	);
};
