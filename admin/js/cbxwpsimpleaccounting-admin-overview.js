//console.log('accounting js loaded');
(function ($) {
    'use strict';

    $(document).ready(function () {

        //delete any expinc
        $(".cbxdelexpinc").click(function (e) {
            e.preventDefault();

            if (!confirm(cbxwpsimpleaccounting.permission)) {
                return false;
            }

            var $this = $(this);
            var $id = $this.attr('id')
            $('#cbxaccountingloading').show();
            var data = {
                'action': 'delete_expinc',
                'id': $id,
                'security': cbxwpsimpleaccounting.nonce
            };
            //ajax call for deleting expinc
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: data,
                success: function (response) {
                    if (!response.error) {
                        $('#cbxaccountingloading').hide();
                        $this.closest("tr").remove();
                        $('.msg').show();
                        $('.msg').html(response.msg);
                    }
                    else {
                        $('#cbxaccountingloading').hide();
                        $('.msg').hide();
                        $('.msg').html(response.msg);
                    }
                }

            });//end ajax calling for category
        });
    }); //end DOM ready

})(jQuery);
