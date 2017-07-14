function cbx_goToByScroll(id) {
    // Remove "link" from the ID
    id = id.replace("link", "");
    // Scroll
    jQuery('html,body').animate({
        scrollTop: jQuery("#" + id).offset().top},
    'slow');
}

(function ($) {
    'use strict';

    var serializeObject = function ($form, wp_action_name) {
        var o = {};
        o['action'] = wp_action_name;
        var a = $form.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    $(document).ready(function ($) {

        //add color picker
        var colorpicker_options = {
             //hide: false
        };

        $('.cbacnt-cat-color-picker').wpColorPicker(colorpicker_options);
        //all category
        var $all_cat_list = $.parseJSON(cbxwpsimpleaccounting.cat_results_list);

        //list the category by group
        var $catselection = $('.cbacnt-exinc-type').val();

        $.each($all_cat_list, function (index, cat) {
            if ($.isEmptyObject(cat))
                return;
            if (cat.type == 1) {
                $('#cbacnt-expinc-cat-list').append('<li class="cbacnt-cat-exinc cbacnt-cat-inc cbacnt-cat-type-1 ' + (($catselection == 2) ? 'hidden' : '') + ' " style="color:' + cat.color + '">' + cat.title.replace(/\\/g, '') + ' <a data-catid="' + cat.id + '" class="cbacnt-edit-cat" href="#">' + cbxwpsimpleaccounting.edit + '</a></li>');
            }
            else {
                $('#cbacnt-expinc-cat-list').append('<li class="cbacnt-cat-exinc cbacnt-cat-inc cbacnt-cat-type-2 ' + (($catselection == 1) ? 'hidden' : '') + ' " style="color:' + cat.color + '">' + cat.title.replace(/\\/g, '') + ' <a data-catid="' + cat.id + '" class="cbacnt-edit-cat" href="#">' + cbxwpsimpleaccounting.edit + '</a></li>');
            }
        });

        //dynamically change the selectbox bottom listing based on the selection of the top selectbox of "Type" 
        $('.cbacnt-cat-type').on('change', function () {
            
            var $catselection = $(this).val();
            
            $("input[name=cbacnt-exinc-type][value=" + $catselection + "]").prop('checked', true);
           
            if ($catselection == 1) {
                $('.cbacnt-cat-type-2').addClass('hidden');
                $('.cbacnt-cat-type-1').removeClass('hidden');
            }
            else {
                $('.cbacnt-cat-type-1').addClass('hidden');
                $('.cbacnt-cat-type-2').removeClass('hidden');
            }

        });

        //create new category
        $('#cbacnt-cat-form').submit(function (evnt) {
            evnt.preventDefault();
            var $form = $(this);

            $('#cbxaccountingloading').show();
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: serializeObject($form, 'add_new_expinc_cat'),
                success: function (response) {
                    //clear all error and update field
                    $form.find('.cbacnt-cat').removeClass('cbacnt-error');
                    $form.find('#cbacnt-edit-cat-cancel').attr('disabled', 'disabled');

                    if (response.error) {
                        $('#cbxaccountingloading').hide();
                        $form.find('.cbacnt-msg-text').text(response.msg);
                        $form.find('.cbacnt-msg-box').addClass('error').removeClass('updated hidden').show();
                        $.each(response.field, function (index, value) {
                            $form.find('label[for="' + value + '"], #' + value).addClass('cbacnt-error');
                        });
                    } else {
                        $('#cbxaccountingloading').hide();
                        $all_cat_list[response.form_value.id] = response.form_value;
                        $form.find('.cbacnt-msg-text').html(response.msg);
                        $form.find('.cbacnt-msg-box').addClass('updated').removeClass('error hidden').show();

                        //reset form is new item inserted
                        if (response.form_value.status == 'new') {
                            $form[0].reset();
                        }
                        //refresh category list
                        //$('#cbacnt-exinc-type').val(response.form_value.type).trigger('chosen:updated');
                        var $catselection = $('.cbacnt-exinc-type').val();
                        $('#cbacnt-expinc-cat-list').html('');

                        $.each($all_cat_list, function (index, cat) {
                            if ($.isEmptyObject(cat))
                                return;
                            if (cat.type == 1) {
                                $('#cbacnt-expinc-cat-list').append('<li class="cbacnt-cat-exinc cbacnt-cat-inc cbacnt-cat-type-1 ' + (($catselection == 2) ? 'hidden' : '') + ' " style="color:' + cat.color + '">' + cat.title.replace(/\\/g, '') + ' <a data-catid="' + cat.id + '" class="cbacnt-edit-cat" href="#">' + cbxwpsimpleaccounting.edit + '</a></li>');
                            }
                            else {
                                $('#cbacnt-expinc-cat-list').append('<li class="cbacnt-cat-exinc cbacnt-cat-inc cbacnt-cat-type-2 ' + (($catselection == 1) ? 'hidden' : '') + ' " style="color:' + cat.color + '">' + cat.title.replace(/\\/g, '') + ' <a data-catid="' + cat.id + '" class="cbacnt-edit-cat" href="#">' + cbxwpsimpleaccounting.edit + '</a></li>');
                            }
                        });

                    }
                }
            });//end ajax calling for category
        });//end category form submission


        //if click on 'Add New' button
        $('#cbacnt-cat-form').on('click', '.cbacnt-new-cat, #cbacnt-edit-cat-cancel', function (event) {
            
            event.preventDefault();
            var $form = $(this).parents('#cbacnt-cat-form');

            $form.find('#cbacnt-edit-cat-cancel').attr('disabled', 'disabled');
            $form.find('#cbacnt-cat-id').val('0');
            $form.find('#cbacnt-new-cat').val($form.find('#cbacnt-new-cat').data('add-value'));
            $form.find('input[name=cbacnt-cat-type][value=' + 1 + ']').prop('checked', true);
            $('input[name=cbacnt-exinc-type][value=' + 1 + ']').prop('checked', true);
            $form[0].reset();
            $form.find('.cbacnt-msg-box').fadeOut();
        });

        //if click on edit category from category listing
        $('#cbxaccounting_catmanager').on('click', '.cbacnt-edit-cat', function (event) {
            event.preventDefault();

            var $form = $('#cbacnt-cat-form');

            var data = {};
            data['action'] = 'load_expinc_cat';
            data['catid'] = parseInt($(this).data('catid'));
            data['nonce'] = cbxwpsimpleaccounting.nonce

            $('#cbxaccountingloading').show();
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajaxurl,
                data: data,
                success: function (response) {
                    $('#cbxaccountingloading').hide();
                    if (response.error) {
                        $form.find('.cbacnt-msg-text').text(response.msg);
                        $form.find('.cbacnt-msg-box').addClass('error').removeClass('updated hidden').show();
                        cbx_goToByScroll('cbxaccounting_catmanager');
                    } else {
                        $('#cbxaccountingloading').hide();
                        $form.find('.cbacnt-msg-text').html(response.msg);
                        $form.find('#cbacnt-cat-id').val(response.form_value.id);
                        $form.find('#cbacnt-cat-title').val(response.form_value.title);
                        $form.find("input[name=cbacnt-cat-type][value=" + response.form_value.type + "]").prop('checked', true);
                        $form.find('#cbacnt-cat-color').iris('color', response.form_value.color);//showing saved color
                        $form.find('#cbacnt-cat-note').val(response.form_value.note);
                        $form.find('#cbacnt-new-cat').val($form.find('#cbacnt-new-cat').data('update-value'));
                        $form.find('#cbacnt-edit-cat-cancel').removeAttr('disabled');
                        cbx_goToByScroll('cbxaccounting_catmanager');
                    }
                }
            });//end ajax calling for category
        });

        $('.cbacnt-exinc-type').on('change', function () {
            $('#cbacnt-expinc-cat-list').find('.cbacnt-cat-exp, .cbacnt-cat-inc').toggleClass('hidden');
        });

    }); //end DOM ready

})(jQuery);
