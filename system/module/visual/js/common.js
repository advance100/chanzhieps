$(function()
{
    if($('.ve-form').length)
    {
        $.setAjaxForm('.ve-form', function(data)
        {
            if(data.result === 'success')
            {
                var parent$ = window.parent.$
                parent$.closeModal();
                parent$.updateVisualArea(data);
            }
        });
    }
});