<div class="panel">
    <div class="panel-heading">
        <i class="icon-eye-open"></i> {l s='Live Preview & Test' mod='customemailmanager'}
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info">
                {l s='Select a template below to preview it with sample data.' mod='customemailmanager'}
            </div>
            <iframe id="template_preview" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
        </div>
        <div class="col-lg-4">
            <div class="well">
                <h4>{l s='Send Test Email' mod='customemailmanager'}</h4>
                <input type="email" id="test_email_address" class="form-control" placeholder="your-email@example.com">
                <br>
                <button type="button" class="btn btn-primary btn-block" onclick="sendTestEmail()">
                    <i class="icon-envelope"></i> {l s='Send Test to Me' mod='customemailmanager'}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function sendTestEmail() {
        var email = $('#test_email_address').val();
        if(!email) { alert('Please enter an email address'); return; }
        
        $.ajax({
            type: 'POST',
            url: currentIndex + '&token=' + token + '&ajax=1&action=SendTestEmail',
            data: { test_email: email },
            success: function(r) { alert('Test email sent!'); }
        });
    }
</script>
