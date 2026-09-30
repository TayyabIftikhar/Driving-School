// Basic validation example
function validateForm(form) {
    let valid = true;
    form.querySelectorAll('input[required], select[required]').forEach(input => {
        if (!input.value) {
            alert(`${input.name} is required`);
            valid = false;
        }
    });
    if (form.querySelector('input[type="email"]') && !/\S+@\S+\.\S+/.test(form.querySelector('input[type="email"]').value)) {
        alert('Invalid email');
        valid = false;
    }
    return valid;
}

// Simple calendar (for scheduling demo)
function loadCalendar() {
    const calendar = document.querySelector('.calendar');
    if (calendar) {
        for (let i = 1; i <= 31; i++) {
            const day = document.createElement('div');
            day.textContent = i;
            calendar.appendChild(day);
        }
    }

}
document.addEventListener('DOMContentLoaded', loadCalendar);
// Form validation
function validateForm(form) {
    let valid = true;
    form.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
        if (!input.value.trim()) {
            alert(`${input.name} is required`);
            valid = false;
        }
    });
    const emailInput = form.querySelector('input[type="email"]');
    if (emailInput && !/\S+@\S+\.\S+/.test(emailInput.value)) {
        alert('Invalid email format');
        valid = false;
    }
    return valid;
}

// Load simple calendar for scheduling
function loadCalendar(container) {
    if (container) {
        container.innerHTML = '';
        for (let day = 1; day <= 31; day++) {
            const div = document.createElement('div');
            div.textContent = day;
            div.onclick = () => alert(`Selected date: 2025-09-${day}`);  // Placeholder
            container.appendChild(div);
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    loadCalendar(document.querySelector('.calendar'));
});