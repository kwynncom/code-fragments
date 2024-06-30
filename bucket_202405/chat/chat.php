<!DOCTYPE html>
<html lang='en'> <?php require_once('/opt/kwynn/kwutils.php'); ?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>chat</title>

<style>
    body { font-family: sans-serif; }
</style>

<script src='/opt/kwynn/js/utils.js'></script>
<script>
    class sendcl {
        constructor() {
            this.ta = byid('ta10'); 
            this.btn = byid('sendBtn');
            this.msgi = 0;
        }
        
        send() {
            const ta = this.ta;
            const btn = this.btn;
            
            ta.readonly = ta.disabled = btn.disabled = true;
            const v = ta.value;
            const i = ++this.msgi;
            kwjss.sobf('server.php', {'t' : v, 'i' : i, 'tsmsjs' : time(), 'pageID' : byid('pageID').value });
            const d = cree('div');
            d.innerHTML =  '<div>' +  v + '</div>';
            d.dataset.msgi = i;
            d.style.opacity = 0.3;
            d.style.color = 'blue';
            byid('wholeChat').append(d);
            ta.value = '';
            ta.readonly = ta.disabled = btn.disabled = false;            
        }
      
    }
  
    window.addEventListener('DOMContentLoaded', (event) => {
        const co = new sendcl();
        const f = function() { co.send(); };
        byid('sendBtn').onclick = f;
        byid('ta10').addEventListener('keyup', ({key}) => { if (key === 'Enter') f();  });
    });
    
</script>

</head>
<body>
    <div>
		<?php $pid = dao_generic_3::get_oids(true); ?>
		<input type='hidden' id='pageID' value='<?php echo($pid); unset($pid); ?>' /> 
        <textarea id='ta10'></textarea>
        <button id='sendBtn'>send</button> (or enter key)
    </div>
    <div style='overflow: scroll' id='wholeChat'>
        
    </div>
</body>
</html>

