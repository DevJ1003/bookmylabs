
const addMoneyBtn = document.getElementById('add-money-btn');
const modal = document.getElementById('add-money-modal');
const closeBtn = document.querySelector('.close-btn');

addMoneyBtn.addEventListener('click', () => {
    modal.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});

const form = document.getElementById('add-money-form');
form.addEventListener('submit', (event) => {
    // event.preventDefault();
    
    // Handle form submission logic here
    const username = document.getElementById('username').value;
    const upiReference = document.getElementById('upi-reference').value;
    const amount = document.getElementById('amount').value;

    // Process the data
    console.log('Username:', username);
    console.log('UPI Reference:', upiReference);
    console.log('Amount:', amount);

    // Close the modal
    modal.style.display = 'none';

    // Optionally, clear the form fields
    form.reset();
});
