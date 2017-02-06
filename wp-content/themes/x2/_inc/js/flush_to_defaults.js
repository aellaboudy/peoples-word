(function($){
    $(document).ready(function(){
        
        var meta_boxes = $('#x2_slideshow, #list_posts');
        
        meta_boxes.find('option[value="default"]').attr('selected', true);
        meta_boxes.find('option[value="off"], option[value="hide"]').attr('selected', true);
        meta_boxes.find(':checkbox').attr('checked', false);
        meta_boxes.find(':text').val('');
        
    });
})(jQuery)