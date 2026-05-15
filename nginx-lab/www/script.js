const form = document.getElementById('subscribeForm');
const resultDiv = document.getElementById('result');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    let output = '<h3>Детали подписки:</h3>';
    
    const dataMap = {
        'fullname': 'ФИО',
        'email': 'Email',
        'duration': '⏱Срок подписки',
        'magazine': 'Журнал',
        'digital': 'Электронная версия',
        'payment': 'Способ оплаты'
    };

    for (const [key, value] of formData.entries()) {
        const label = dataMap[key] || key;
        output += `<p><strong>${label}:</strong> ${value}</p>`;
    }

    resultDiv.innerHTML = output;
    resultDiv.style.display = 'block';
    
    clearTimeout(inactivityTimer);
});

let inactivityTimer;
let isSubmitted = false;  

function remindUser() {
    const fullname = document.getElementById('fullname').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!isSubmitted && fullname === '' && email === '') {
        alert('⏰ Напоминание: Пожалуйста, заполните форму подписки!');
        
        if (fullname === '') {
            document.getElementById('fullname').style.border = '2px solid #ff9800';
            document.getElementById('fullname').style.backgroundColor = '#fff3e0';
        }
        if (email === '') {
            document.getElementById('email').style.border = '2px solid #ff9800';
            document.getElementById('email').style.backgroundColor = '#fff3e0';
        }
        
        setTimeout(() => {
            document.getElementById('fullname').style.border = '';
            document.getElementById('fullname').style.backgroundColor = '';
            document.getElementById('email').style.border = '';
            document.getElementById('email').style.backgroundColor = '';
        }, 3000);
    }
}

function resetTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(remindUser, 15000); 
}

const allInputs = document.querySelectorAll('#subscribeForm input, #subscribeForm select');

allInputs.forEach(input => {
    input.addEventListener('input', resetTimer);  
    input.addEventListener('change', resetTimer); 
    input.addEventListener('click', resetTimer);  
});

form.addEventListener('submit', () => {
    isSubmitted = true;
    clearTimeout(inactivityTimer);
});

resetTimer();

document.addEventListener('mousemove', resetTimer);
document.addEventListener('keydown', resetTimer);