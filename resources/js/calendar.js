var currentYear;
var currentMonth;

document.addEventListener("DOMContentLoaded", function(){
    const currentDate = new Date();
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
function updateCalendar(year, month) {
    const weeksContainer = document.getElementById('weeks');
    weeksContainer.innerHTML = '';

    getCalendarData(year, month)
        .then(data => {
            console.log(data);
            if (!data) {
                console.error('No data returned from the API.');
                return;
            }

            data.forEach(week => {
                const weekDiv = document.createElement('div');
                weekDiv.classList.add('week');

                week.forEach(day => {
                    const dayButton = document.createElement('button');
                    dayButton.classList.add('dayWrapper');

                    const dayParagraph = document.createElement('p');
                    dayParagraph.classList.add('day', 'calendarDay');
                    dayParagraph.textContent = day.date;

                    if (!day.in_current_month) {
                        dayParagraph.style.opacity = '0.5';
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
