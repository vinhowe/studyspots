<html>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55118707-8"></script>
<script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
                dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-55118707-8');
</script>

<?php
$dayofweek = date('w');
$dayname = date('l, F j @ H:i');
?>

<head>
        <title>Open BYU classrooms today</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <style>
                body {
                        margin: 0;
                        width: 100%;
                }

                main {
                        padding: 1.5em;
                }

                footer {
                        padding: 0 1.5em 1.5em;
                }

                #hours {
                        overflow-x: scroll;
                }

                #hours,
                td,
                td {
                        border: 1px solid lightgray;
                        border-collapse: collapse;
                        padding: 10px;
                }

                td {
                        background-color: #f0f0f0;
                }

                .icon-cell {
                        text-align: center;
                        background-color: transparent;
                }

                .deemphasized {
                        opacity: 0.75;
                }

                .piano {
                        background-color: #ddFFdd;

                }

                * {
                        font-family: "Lato", sans-serif;
                }

                .icon-cell a {
                        text-decoration: none;
                }

                .button {
                        background: linear-gradient(#fff, #eee);
                        ;
                        color: black;
                        border: 1px solid darkgray;
                        border-radius: 4px;
                        padding: 4px;
                        display: inline-block;
                        transition: all 200ms;
                        text-align: center;
                        cursor: pointer;
                }

                .image-button {
                        width: calc(100% - 10px);
                }

                .button:hover {
                        background: #ccc;
                }

                .button:active {
                        background: #999;
                        border-color: #212121;
                }

                .table-wrapper {
                        overflow-x: auto;
                        margin: 0 calc(-1.5em / 2);
                }

                /* Based on https://html-online.com/articles/simple-popup-box/ */

                /* Popup box BEGIN */
                #hover-wrapper {
                        background: rgba(0, 0, 0, .4);
                        cursor: pointer;
                        display: none;
                        height: 100vh;
                        position: fixed;
                        text-align: center;
                        top: 0;
                        width: 100vw;
                        z-index: 10000;
                }

                #hover-wrapper .hover-center {
                        display: inline-block;
                        height: 100%;
                        vertical-align: middle;
                }

                #hover-wrapper>div {
                        display: inline-block;
                        height: 90%;
                        max-width: 90%;
                        min-height: 100px;
                        vertical-align: middle;
                        width: 90%;
                        position: relative;
                        border-radius: 4px;
                }

                #hover-wrapper>div>iframe {
                        height: 100%;
                        width: 100%;
                        border-radius: 4px;
                }

                #popup-close-button {
                        display: inline-block;
                        /* font-weight: bold; */
                        margin: 4px;
                        position: absolute;
                        right: 0;
                        font-size: 25px;
                        line-height: 30px;
                        width: 30px;
                        height: 30px;
                }

                @media(max-width: 500px) {
                        .hide-mobile {
                                display: none;
                        }
                }       

                /* Popup box END */
        </style>
</head>

<body>
        <div id="hover-wrapper">
                <span class="hover-center"></span>
                <div>
                        <iframe id="360-iframe" src="" frameborder="0"></iframe>
                        <div id="popup-close-button" class="button">x</div>
                </div>
        </div>
        <main>

                <h1>Open BYU classrooms winter semester 2020 for <?php echo "$dayname" ?> (MST)</h1>
                <p>Use BYU's <a href="https://y.byu.edu/class_schedule/cgi/classRoom.cgi" target="_blank">Classroom Information</a> page to view more info about individual classrooms.</p>
                <p>Piano information may be incorrect for some classrooms. Use the 360° images to verify.</p>
                <p class="hide-mobile">Classrooms are sorted (in order):</p>
                <ol class="hide-mobile">
                        <li>Low-high by soonest open time</li>
                        <li>High-low by length of the first scheduled range</li>
                        <li>Yes-no by whether there's a piano (remember to check )</li>
                        <li>Alphabetically by building name abbreviation</li>
                        <li>Alphanumerically by room number</li>
                </ol>
                <p>This doesn't account for exams. Use BYU's <a href="https://y.byu.edu/ry/ae/prod/class_schedule/cgi/examDailySchedule.cgi" target="_blank">daily exam schedule</a> page to check when exams will be held in classrooms.</p>
                <p>Scrollable on mobile.</p>

                <div class="table-wrapper">
                        <?php
                        $adjusted_day_of_week = $dayofweek - 1;
                        echo "<table id='hours'>\n\n";
                        $f = fopen("classroom_info/${adjusted_day_of_week}_classrooms.csv", "r");
                        while (($line = fgetcsv($f)) !== false) {
                                echo "<tr>";
                                foreach ($line as $cell) {
                                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                                }
                                echo "</tr>\n";
                        }
                        fclose($f);
                        echo "\n</table>";
                        ?>
                </div>
        </main>
        <footer>
                <p>A project by <a href="https://vinhowe.github.io">Vin Howe</a>.</p>
        </footer>
</body>

<script type="text/javascript">
        const minIndex = 2;
        const startHour = 7;
        const endHour = 22;
        const maxHourPos = endHour - startHour;
        const maxHourPosOffset = maxHourPos + minIndex;

        function findFirstHourRangeInRow(currentHour, elements) {
                let currentHourPos = currentHour - 7;
                let startPos = Infinity;
                for (let i = currentHourPos; i < maxHourPos; i++) {
                        if (elements[i]) {
                                startPos = i;
                                break;
                        }
                }

                // No more hours in the day are available
                if (startPos == Infinity) {
                        return [Infinity, -Infinity]
                }

                let endPos = startPos;

                for (let i = startPos; i < maxHourPos; i++) {
                        if (!elements[i]) {
                                break;
                        }
                        endPos = i;
                }

                return [startPos, endPos]
        }

        function compareRows(currentHour) {
                return (a, b) => {
                        // This takes the cake for hackiest, laziest thing I've ever done
                        if (a.children[0].innerText == "Bldg") {
                                return -1;
                        }

                        if (b.children[0].innerText == "Bldg") {
                                return 1;
                        }

                        let nextRangeA = [];
                        let nextRangeB = [];

                        let currentHourPos = (currentHour - 7) + minIndex;


                        let aArray = [].slice.call(a.children);
                        let bArray = [].slice.call(b.children);
                        let aChildren = aArray
                                .filter(el => el.innerText == "False" || el.innerText == "True")
                                .map(el => el.innerText == "True")
                        let bChildren = bArray
                                .filter(el => el.innerText == "False" || el.innerText == "True")
                                .map(el => el.innerText == "True")
                        let aHasPiano = aArray[aArray.length - 1].innerText == "piano";
                        let bHasPiano = bArray[bArray.length - 1].innerText == "piano";
                        let aRange = findFirstHourRangeInRow(currentHour, aChildren)
                        let bRange = findFirstHourRangeInRow(currentHour, bChildren)

                        // `a` should be ordered above `b` if its starting hour is lower
                        if (aRange[0] < bRange[0]) {
                                return -1;
                        }

                        if (aRange[0] > bRange[0]) {
                                return 1;
                        }

                        // If we've reached here, they must start at the same time

                        // `a` should be ordered above `b` if its ending hour is higher
                        if (aRange[1] < bRange[1]) {
                                return 1;
                        }

                        if (aRange[1] > bRange[1]) {
                                return -1;
                        }

                        if (aHasPiano) {
                                return -1;
                        }

                        if (bHasPiano) {
                                return 1;
                        }

                        // Otherwise, they start and end at the same time, and it's all the same

                        return 0;
                }
        }

        window.onload = () => {
                console.log("onload called")
                let currentDate = new Date()
                let hours = currentDate.getHours()
                let tableBody = document.querySelector('#hours').children[0]
                let tableElements = [...tableBody.children]

                // Example classroom image
                // https://classroom-images-prd.s3.amazonaws.com/HFAC/a232t.jpg

                // Algorithm should work as follows:
                // Sorts by the one that is available now that has the most hours in the future available.
                // If it's a tie, it's picked arbitrarily; doesn't matter.
                // We need to create an array that will be fed back into the table
                // It would honestly be better if we could get the data from the CSV file and feed it through JS.
                // What is stopping us from doing that? Wouldn't it be easier to just come up with an API for this

                tableElements = tableElements.sort(compareRows(hours))

                tableBody.innerHTML = ""

                for (let element of tableElements) {
                        tableBody.appendChild(element)
                }

                let imageHeader = document.createElement('td')
                imageHeader.innerText = "360° image"
                tableElements[0].append(imageHeader)

                for (let i = 1; i < tableElements.length; i++) {
                        let imageCell = document.createElement('td')
                        let imageLink = document.createElement('a')
                        let bldgName = tableElements[i].children[0].innerText.toUpperCase();
                        let roomName = tableElements[i].children[1].innerText.toLowerCase();

                        imageLink.addEventListener("click", () => openPopup(bldgName, roomName));

                        imageLink.className = "button image-button"
                        imageLink.innerText = "📷"
                        imageLink.href = '#'
                        imageLink.onclick = () => false;
                        imageCell.className = "icon-cell";
                        imageCell.append(imageLink)
                        tableElements[i].append(imageCell)
                }

                let cells = [].slice.call(document.querySelectorAll('td'));
                let trueCells = cells.filter(txt => txt.innerText.includes('True'))
                let falseCells = cells.filter(txt => txt.innerText.includes('False'))

                trueCells.forEach(txt => {
                        txt.innerText = "✅";
                        txt.className = "icon-cell";
                });

                falseCells.forEach(txt => {
                        txt.innerText = "❌";
                        txt.className = "icon-cell";
                });

                let pianoCells = cells.filter(txt => txt.innerText.includes('piano'))
                let pia_noCells = cells.filter(txt => txt.innerText.includes('pia-no'))

                pianoCells.forEach(txt => {
                        txt.innerText = "🎹";
                        txt.className = "icon-cell piano";
                });

                pia_noCells.forEach(txt => {
                        txt.innerText = "";
                        txt.className = "icon-cell piano";
                });

                // console.log(tableElements)

                if (hours > 7) {
                        const minIndex = 2
                        const maxIndex = (hours - 7) + minIndex;

                        for (let i = 0; i < tableElements.length; i++) {
                                let row = [...tableElements[i].children]
                                for (let j = minIndex; j < maxIndex; j++) {
                                        row[j].className = 'icon-cell deemphasized'
                                }
                        }
                }

                document.getElementById("popup-close-button").addEventListener("click", closePopup);
        }

        function closePopup() {
                console.log('w')
                document.getElementById('hover-wrapper').style.display = 'none';
        }

        function openPopup(bldg, room) {
                let imageUrl = `https://${window.location.host}/360.php?bldg=${bldg}&room=${room}`
                document.getElementById('360-iframe').src = imageUrl;
                document.getElementById('hover-wrapper').style.display = 'initial';

                // Prevent jumping to the top of the page
                return false;
        }
</script>

</html>
