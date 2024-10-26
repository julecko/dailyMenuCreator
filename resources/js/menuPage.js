var currentYear;
var currentMonth;
var currentDate;

document.addEventListener("DOMContentLoaded", function(){
    const params = new URLSearchParams(window.location.search);
    const dateParam = params.get('date');

    currentDate = dateParam ? new Date(dateParam) : new Date();

    currentYear = currentDate.getFullYear();
    currentMonth = currentDate.getMonth() + 1;
    updateCalendar(currentYear, currentMonth);
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');

    leftArrow.addEventListener('click', function(){
        if (currentMonth === 1){
            currentMonth = 12;
            currentYear -= 1;
        }else{
            --currentMonth;
        }
        updateCalendar(currentYear, currentMonth)
    })
    rightArrow.addEventListener('click', function(){
        if (currentMonth === 12){
            currentMonth = 1;
            currentYear += 1;
        }else{
            ++currentMonth;
        }
        updateCalendar(currentYear, currentMonth)
    })
    setUpFoodButtons(currentDate);
    setUpSideButtons(currentDate);

})
function getMonthName(monthNumber) {
    switch (monthNumber) {
        case 1: return "Január";
        case 2: return "Február";
        case 3: return "Marec";
        case 4: return "Apríl";
        case 5: return "Máj";
        case 6: return "Júj";
        case 7: return "Júl";
        case 8: return "August";
        case 9: return "September";
        case 10: return "Október";
        case 11: return "November";
        case 12: return "December";
        default: return "Invalid month number";
    }
}
async function getCalendarData(year, month) {
    try {
        const postData = {
            year,
            month
        }
        const response = await fetch("/api/calendar", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(postData),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }

        const data = await response.json();

        return data;
    } catch (error) {
        return 'There has been a problem with your fetch operation:';
    }
}
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}
function updateCalendar(year, month) {
    const weeksContainer = document.getElementById('weeks');
    weeksContainer.innerHTML = '';

    getCalendarData(year, month)
        .then(data => {
            if (!data) {
                console.error('No data returned from the API.');
                return;
            }

            data.forEach(week => {
                const weekDiv = document.createElement('div');
                weekDiv.classList.add('week');

                week.forEach(day => {
                    const dayButton = document.createElement('button');
                    dayButton.addEventListener('click', function(){
                        const url = new URL(window.location.href);
                        url.searchParams.set('date', day.date);
                        window.history.pushState({}, '', url);
                        window.location.reload();
                    })

                    const dayParagraph = document.createElement('p');
                    dayParagraph.classList.add('day', 'calendarDay');
                    dayParagraph.textContent = String(new Date(day.date).getDate());

                    const currentDay = currentDate.getDate();
                    if (parseInt(dayParagraph.textContent) === currentDay){
                        dayButton.classList.add('dayWrapper', 'dayWrapperCurrent');
                    }else{
                        dayButton.classList.add('dayWrapper', 'dayWrapperNormal');
                    }

                    if (!day.in_current_month) {
                        dayParagraph.style.opacity = '0.5';
                    }
                    if (day.exists){
                        dayParagraph.style.color = 'red';
                    }
                    dayButton.appendChild(dayParagraph);
                    weekDiv.appendChild(dayButton);
                });

                weeksContainer.appendChild(weekDiv);
            });
            const title = document.getElementById('calendarTitle');
            const month = getMonthName(currentMonth);
            title.innerHTML = `${month} ${currentYear}`;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
function sendPostRequest(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            return response.text().then(text => {
                console.log('Raw response:', text);
                return text;
            });
        })
        .then(text => {
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Error parsing JSON:', e);
                throw e;
            }
            if (data.success) {
                console.log('Success:', data);
            } else {
                console.error('Error:', data.message || 'Request was unsuccessful');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}
function setUpFoodButtons(currentDate) {
    const manualButtons = document.querySelectorAll('.manualChoice');
    manualButtons.forEach(button => {
        button.addEventListener('click', function() {
            const data = {
                date: formatDate(currentDate),
                update_type: 'manual',
                food_type: button.id
            };
            sendPostRequest('/api/update', data).then(() => {
                location.reload();
            });
        });
    });

    const deleteButtons = document.querySelectorAll('.deleteChoice');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const data = {
                date: formatDate(currentDate),
                update_type: 'delete',
                food_type: button.id
            };
            sendPostRequest('/api/update', data).then(() => {
                location.reload();
            });
        });
    });
}

function setUpSideButtons(currentDate) {
    const generateButton = document.getElementById('generateButton');
    generateButton.addEventListener('click', function() {
        const data = {
            date: formatDate(currentDate),
        };
        sendPostRequest('/api/generate', data).then(() => {
            location.reload();
        });
    });
}
