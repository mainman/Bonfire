function verify_delete(whom, action) {
	var date_for = 'for';
	if (action.indexOf('date') != -1) date_for = 'before';

    var verify = confirm('Are you sure you wish to delete the activity logs '+date_for+' "'+whom+'"?')

    if (verify) {
        var url = '<?php echo site_url(SITE_AREA .'/reports/activities/delete/') ?>/'+ action;
        window.location.href = url
    }

    return false;
}

$('.btn').click( function() {
	var which = $(this).attr('id').replace('delete-', '');
	var whom = $('#'+which+'_select option:selected').text();
	var action = which + '/' + $('#'+which+'_select option:selected').val();

	event.stopImmediatePropagation();
	event.preventDefault();
	verify_delete(whom,action);
});

<?php
/*
 @TODO : FIX OR REMOVE THIS CODE
 This is debugging code
$("#flex_table").dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "iDisplayLength": <?php echo config_item('site.list_limit') ? config_item('site.list_limit') : 15; ?>,
        "bAutoWidth": false,
        "aoColumns": [
            { "sWidth": "10%" },
            null,
            { "sWidth": "8em" },
            { "sWidth": "12em" }
        ],
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        }
});
*/
?>

$("#flex_table").dataTable({
		"sDom": 'rt<"top"fpi>',
		"sPaginationType": "listbox",
		"bProcessing": true,
		"bLengthChange": false,
		"iDisplayLength": <?php echo config_item('site.list_limit') ? config_item('site.list_limit') : 15; ?>,
		"aaSorting": [[3,'desc']],
		"bAutoWidth": false,
		"aoColumns": [
			{ "sWidth": "10%" },
			null,
			{ "sWidth": "8em" },
			{ "sWidth": "12em" }
		],
                "oLanguage": {
                    "sProcessing":   "<?php echo lang('sProcessing') ?>",
                    "sLengthMenu":   "<?php echo lang('sLengthMenu') ?>",
                    "sZeroRecords":  "<?php echo lang('sZeroRecords') ?>",
                    "sInfo":         "<?php echo lang('sInfo') ?>",
                    "sInfoEmpty":    "<?php echo lang('sInfoEmpty') ?>",
                    "sInfoFiltered": "<?php echo lang('sInfoFiltered') ?>",
                    "sInfoPostFix":  "<?php echo lang('sInfoPostFix') ?>",
                    "sSearch":       "<?php echo lang('sSearch') ?>",
                    "sUrl":          "<?php echo lang('sUrl') ?>",
                    "oPaginate": {
                        "sFirst":    "<?php echo lang('sFirst') ?>",
                        "sPrevious": "<?php echo lang('sPrevious') ?>",
                        "sNext":     "<?php echo lang('sNext') ?>",
                        "sLast":     "<?php echo lang('sLast') ?>"
                    }
                }
});
