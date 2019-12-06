<html>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55118707-8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-55118707-8');
</script>
<?php
date_default_timezone_set('America/Denver');
$dayofweek = date('w');
$dayname = date('l, F j @ H:i');
?>

<head>
        <title>Open BYU classrooms today</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
		#hours {
			overflow-x: scroll;		
		}

                #hours,
                td,
                td {
                        border: 1px solid #aaa;
                        border-collapse: collapse;
                        padding: 10px;
                }

                .hour {
                        text-align: center;
                }

                .deemphasized {
			display: none;
                }

                body {
                        padding: 1.5em;
                }

                html {
                        font-family: "Lato", sans-serif;
                }
        </style>
</head>

<body>
        <main>
                <h1>Open BYU classrooms for <?php echo "$dayname" ?> (MST)</h1>
                <p>Use BYU's <a href="https://y.byu.edu/class_schedule/cgi/classRoom.cgi">Classroom Information</a> page to view more info about individual classrooms.</p>
                <?php
                $adjusted_day_of_week = $dayofweek - 1;
                echo "<table id='hours'>\n\n";
                $f = fopen("class_info/${adjusted_day_of_week}_classes.csv", "r");
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

                        let aChildren = [].slice.call(a.children)
                                .filter(el => el.innerText == "False" || el.innerText == "True")
                                .map(el => el.innerText == "True")
                        let bChildren = [].slice.call(b.children)
                                .filter(el => el.innerText == "False" || el.innerText == "True")
                                .map(el => el.innerText == "True")
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
                // Not sure why this doesn't just work
                Array.prototype.slice.call(document.querySelectorAll('td'))
                        .filter(txt => txt.innerText.includes('True'))
                        .forEach(txt => {
                                txt.innerText = "✅";
                                txt.className = "hour";
                        });
                [].slice.call(document.querySelectorAll('td'))
                        .filter(txt => txt.innerText.includes('False'))
                        .forEach(txt => {
                                txt.innerText = "❌";
                                txt.className = "hour";
                        });
                [].slice.call(document.querySelectorAll('td'))

                // console.log(tableElements)

                if (hours > 7) {
                        const minIndex = 2
                        const maxIndex = (hours - 7) + minIndex;

                        for (let i = 0; i < tableElements.length; i++) {
                                let row = [...tableElements[i].children]
                                for (let j = minIndex; j < maxIndex; j++) {
                                        row[j].className = 'hour deemphasized'
                                }
                        }
                }
        }
</script>

</html>
