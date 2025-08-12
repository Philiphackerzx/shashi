
const showEncrypt = document.getElementById('showEncrypt');
const showDecrypt = document.getElementById('showDecrypt');
const encryptForm = document.getElementById('encryptForm');
const decryptForm = document.getElementById('decryptForm');
const cancelEncrypt = document.getElementById('cancelEncrypt');
const cancelDecrypt = document.getElementById('cancelDecrypt');
const result = document.getElementById('result');

showEncrypt.addEventListener('click', ()=>{
  encryptForm.classList.remove('hidden');
  decryptForm.classList.add('hidden');
  result.classList.add('hidden');
  showEncrypt.style.backgroundColor = "#c0ff00"; showEncrypt.style.color = "#000000";
  showDecrypt.style.backgroundColor = "#000000"; showDecrypt.style.color = "#666666";
  
});
showDecrypt.addEventListener('click', ()=>{
  decryptForm.classList.remove('hidden');
  encryptForm.classList.add('hidden');
  result.classList.add('hidden');
  showEncrypt.style.backgroundColor = "#000000"; showEncrypt.style.color = "#666666";
  showDecrypt.style.backgroundColor = "#c0ff00"; showDecrypt.style.color = "#000000";
});
cancelEncrypt.addEventListener('click', ()=>encryptForm.classList.add('hidden'));
cancelDecrypt.addEventListener('click', ()=>decryptForm.classList.add('hidden'));

encryptForm.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const txt = document.getElementById('message').value.trim();
  if(!txt) return alert('Please write a message');
  const fd = new FormData();
  fd.append('action','encrypt');
  fd.append('message', txt);
  const resp = await fetch('api.php',{method:'POST',body:fd});
  const j = await resp.json();
  if(j.success){
    encryptForm.classList.add('hidden');
    showResult(`<strong>Encrypted !</strong><div>Key: <code style="color:var(--accent);font-weight:700">${j.key}</code> <button class="copyBtn" data-key="${j.key}">Copy</button></div><div style="margin-top:8px;color:var(--muted);font-size:0.9rem">Share this key. Note: Message can be viewed only once !</div>`);
    document.querySelector('.copyBtn').addEventListener('click', (ev)=>{
      navigator.clipboard.writeText(ev.target.dataset.key);
      ev.target.textContent = 'Copied';
      setTimeout(()=>ev.target.textContent='Copy',1200);
    });
    document.getElementById('message').value='';
  } else {
    alert(j.error || 'Failed to store');
  }
});

decryptForm.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const key = document.getElementById('keyInput').value.trim();
  if(!key) return alert('Enter key');
  const fd = new FormData();
  fd.append('action','decrypt');
  fd.append('key', key);
  const resp = await fetch('api.php',{method:'POST',body:fd});
  const j = await resp.json();
  if(j.success){
    decryptForm.classList.add('hidden');
    showResult(`<strong style="color:#c0ff0044">Message:</strong><pre style="white-space:pre-wrap;color:var(--muted);margin-top:8px">${escapeHtml(j.message)}</pre><div style="margin-top:10px;color:var(--muted);font-size:0.9rem"><strong style="color:#c0ff0044">Note: This message cannot be viewed again !</strong></div>`);
    document.getElementById('keyInput').value='';
  } else {
    alert(j.error || 'Not found or already viewed');
  }
});

function showResult(html){
  result.innerHTML = html;
  result.classList.remove('hidden');
}

function escapeHtml(s){
  return s.replace(/[&<>\"']/g, function(c){
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c];
  });
}