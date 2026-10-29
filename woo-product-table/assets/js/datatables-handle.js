(function($) {
    'use strict';
    $(document).ready(function() {

        let table_selector = '.wpt-datatable .wpt-table-tag.wpt_product_table';

        if (typeof WPT_DATATABLE !== 'undefined') {
            $(table_selector).each(function() {
                var $table = $(this);
                if (!$.fn.DataTable.isDataTable(this)) {
                    var $wrapper = $table.closest('.wpt_product_table_wrapper');
                    var $statsReport = $wrapper.find('.wpt-stats-report');

                    $table.on('draw.dt', function() {
                        var api = $table.DataTable();
                        var info = api.page.info();
                        if ($statsReport.length && info) {
                            var start = info.recordsDisplay > 0 ? (info.start + 1) : 0;
                            var end = info.end;
                            var total = info.recordsDisplay;
                            var currentPage = info.pages > 0 ? (info.page + 1) : 0;
                            var totalPages = info.pages;

                            var $postCountEl = $statsReport.find('.wpt-stats-post-count');
                            var $pageCountEl = $statsReport.find('.wpt-stats-page-count');

                            if ($postCountEl.length) {
                                var postFormat = $postCountEl.attr('data-format') || 'Showing %1$s out of %2$s';
                                var displayCount = start + ' - ' + end;
                                var formattedPost = postFormat
                                    .replace('%1$s', displayCount)
                                    .replace('%2$s', total);
                                if (formattedPost.indexOf('%s') !== -1) {
                                    formattedPost = formattedPost.replace('%s', displayCount).replace('%s', total);
                                }
                                $postCountEl.text(formattedPost);
                            }

                            if ($pageCountEl.length) {
                                var pageFormat = $pageCountEl.attr('data-format') || 'Page %1$s out of %2$s';
                                var formattedPage = pageFormat
                                    .replace('%1$s', currentPage)
                                    .replace('%2$s', totalPages);
                                if (formattedPage.indexOf('%s') !== -1) {
                                    formattedPage = formattedPage.replace('%s', currentPage).replace('%s', totalPages);
                                }
                                $pageCountEl.text(formattedPage);
                            }
                        }
                    });

                    $table.DataTable(WPT_DATATABLE);
                }
            });
        }

        //wpt_query_done
        //wpt_query_progress
        $(document.body).on('wpt_query_done',function(){
            // $(table_selector).off();
            // console.log(myTable);
            // myTable.clear().draw();
        });

        $(document.body).on('click','input.wpt-preview-shortcode-input', function(){
            // alert(3333);
            // $(table_selector).DataTable(WPT_DATATABLE);
        });
    });
})(jQuery);