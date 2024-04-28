window.onload = function () {
    const emailInput = document.getElementById('email');
    const subscribeBtn = document.getElementById('subscribe-btn');
    const unsubscribeBtn = document.getElementById('unsubscribe-btn');

    subscribeBtn.addEventListener('click', () => {
        const email = emailInput.value;
        if (validateEmail(email)) {
            submitForm('/subscribe', email);
        } else {
            alert('Vui lòng nhập địa chỉ email hợp lệ.');
        }
    });

    unsubscribeBtn.addEventListener('click', () => {
        const email = emailInput.value;
        if (validateEmail(email)) {
            submitForm('/unsubscribe', email);
        } else {
            alert('Vui lòng nhập địa chỉ email hợp lệ.');
        }
    });

    function validateEmail(email) {
        // Viết hàm kiểm tra email hợp lệ ở đây
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email);
    }

    function submitForm(url, email) {
        // Tạo form động và gửi request AJAX
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const emailInput = document.createElement('input');
        emailInput.type = 'hidden';
        emailInput.name = 'email';
        emailInput.value = email;

        form.appendChild(csrfInput);
        form.appendChild(emailInput);
        document.body.appendChild(form);
        form.submit();
    }

    const loadBtn = document.getElementById('load-more-btn');

    loadBtn.addEventListener('click', function () {
        var hiddenCards = document.querySelectorAll('.card[style="display: none;"]');
        var numDisplayedCards = document.querySelectorAll('.card:not([style="display: none;"])').length;

        for (var i = 0; i < 4; i++) {
            if (hiddenCards[i]) {
                hiddenCards[i].style.display = 'block';
            } else {
                this.style.display = 'none';
                break;
            }
        }
        var totalCards = document.querySelectorAll('.card').length;
        var numDisplayedCards = document.querySelectorAll('.card:not([style="display: none;"])').length;
        document.getElementById('number-days').textContent = `${numDisplayedCards}-Day Forecast`;
    });

    const dateInput = document.getElementById('date-input');
    const pastBtn = document.getElementById('past-btn');
    const futureBtn = document.getElementById('future-btn');

    flatpickr(dateInput, {
        dateFormat: "Y-m-d",
    });

    pastBtn.addEventListener('click', () => {
        const dateStr = dateInput.value;
        const urlParams = new URLSearchParams(window.location.search);
        const cityName = urlParams.get('city');

        if (isValidPastDate(dateStr)) {
            getData('/past', cityName, dateStr);
        } else {
            alert('Please select a date/time in the past, before January 1, 2015.');
        }
    });

    futureBtn.addEventListener('click', () => {
        const dateStr = dateInput.value;
        const urlParams = new URLSearchParams(window.location.search);
        const cityName = urlParams.get('city');

        if (isValidFutureDate(dateStr)) {
            getData('/future', cityName, dateStr);
        } else {
            alert('Please select a future date, from 14 days to 365 days from the current date.');
        }

    });

    function isValidPastDate(dateStr) {
        const selectedDate = new Date(dateStr);
        const currentDate = new Date();
        const janFirst2015 = new Date('2015-01-01');
        if (selectedDate.toDateString() === currentDate.toDateString()) {
            return false;
        }
        return selectedDate < currentDate && selectedDate > janFirst2015;
    }

    function isValidFutureDate(dateStr) {
        const selectedDate = new Date(dateStr);
        const currentDate = new Date();
        const minFutureDate = new Date(currentDate.getTime() + 14 * 24 * 60 * 60 * 1000);
        const maxFutureDate = new Date(currentDate.getTime() + 365 * 24 * 60 * 60 * 1000);
        return selectedDate > currentDate && selectedDate >= minFutureDate && selectedDate <= maxFutureDate;
    }

    function getData(url, city, date) {
        const form = document.createElement('form');
        form.method = 'get';
        form.action = url;

        const cityInput = document.createElement('input');
        cityInput.type = 'hidden';
        cityInput.name = 'city';
        cityInput.value = city;

        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'date';
        dateInput.value = date;

        form.appendChild(cityInput);
        form.appendChild(dateInput);

        document.body.appendChild(form);
        form.submit();
    }
}