<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>怪獸電力公司萬年曆</title>

    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Varela+Round&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <?php
      $current_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $current_month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $current_date_string = $current_year . "-" . $current_month . "-01";
    $base_timestamp = strtotime($current_date_string);
    $lastMonth = date('m', strtotime('-1 month', $base_timestamp));
    $nextMonth = date('m', strtotime('+1 month', $base_timestamp));

    $today = strtotime("now"); // 1970/01/01 00:00:00 UTC 累積到現在時間的秒數
    $targetDay = date("Y-m-d"); // 今天日期字串，例如今天是2025/11/19
    $Ttime = strtotime($targetDay); // 1970/01/01 00:00:00 UTC 到今天 00:00:00 之間的總秒數
    $month = date("m", $base_timestamp); // 11月
    $Tmonth = date("M");
    $year = date("Y", $base_timestamp);

    $firstDayMonth = date("Y-m-1", $base_timestamp); // 2025-11-1
    $firstWeek = date("w", strtotime($firstDayMonth)); // 0 ~ 6，0是星期日，2025-11-1 輸出6，2025-11-1是星期六
    $monthDays = date("t", $base_timestamp); //11月有30天
    $monthWeeks = ceil(($monthDays + $firstWeek) / 7); //30天 + 6 = 36，36/7=5.14，ceil取6，畫6周
    $tableFirstDay = strtotime("-$firstWeek days", strtotime($firstDayMonth));
    //2025-11-1減6天，第一格是2025-10-26的秒數
    ?>


    <div class="two-pane-container">
        <div class="mini-calendar-pane">
            <h3><?php echo date("F", $base_timestamp); ?></h3>

            <div class="mini-calendar-nav">
                <button class="mini-nav-arrow">
                    <a
                        href="?month=<?php echo $lastMonth ?>&year=<?php echo date('Y', strtotime('-1 month', $base_timestamp)); ?>"><span
                            class="up">
                            <img src="./image/arrow_light_left.png" alt="Previous Month" />
                    </a>
                </button>
                <div class="mini-month-title"><?php echo date("Y", $base_timestamp); ?></div>
                <button class="mini-nav-arrow">
                    <a
                        href="?month=<?php echo $nextMonth ?>&year=<?php echo date('Y', strtotime('+1 month', $base_timestamp)); ?>"><span
                            class="down">
                            <img src="./image/arrow_light_right.png" alt="Next Month" />
                    </a>
                </button>
            </div>

            <div class="mini-weekdays" style="margin-top: 15px">
                <div>S</div>
                <div>M</div>
                <div>T</div>
                <div>W</div>
                <div>T</div>
                <div>F</div>
                <div>S</div>
            </div>

            <div class="mini-days">
                <?php
                for ($i = 0; $i < 42; $i++) {
                    $days = strtotime("+$i day", $tableFirstDay);
                    $color = (date('m', $days) !== $month) ? 'color:lightskyblue' : '';
                    echo '<div style=' . $color . '>' . date('d', $days) . '</div>';
                }
    ?>
            </div>
            <!-- 點擊後，顯示當前時間 -->
            <a href="?month=<?php echo date('m'); ?>&year=<?php echo date('Y'); ?>" class="door-box-link">
                <div class="door-box">
                    <div class="door"></div>
                </div>
            </a>
        </div>

        <div class="main-calendar-pane">
            <div class="header">
                <div class="slogan">WE SCARE BECAUSE WE CARE</div>

                <div class="month-nav-container">
                    <button class="main-nav-arrow" id="prev-month-btn">
                        <a
                            href="?month=<?php echo date('m', strtotime('-1 month', $base_timestamp)); ?>&year=<?php echo date('Y', strtotime('-1 month', $base_timestamp)); ?>">
                            <img src="./image/arrow_light_left.png" alt="Previous Month" />
                        </a>
                    </button>

                    <div class="month-title month"><?php echo date("F", $base_timestamp); ?></div>

                    <div class="month-title year"><?php echo date("Y", $base_timestamp); ?></div>

                    <button class="main-nav-arrow" id="next-month-btn">
                        <a
                            href="?month=<?php echo date('m', strtotime('+1 month', $base_timestamp)); ?>&year=<?php echo date('Y', strtotime('+1 month', $base_timestamp)); ?>">
                            <img src="./image/arrow_light_right.png" alt="Next Month" />
                        </a>
                    </button>
                </div>
            </div>

            <div class="weekdays">
                <div>SUN</div>
                <div>MON</div>
                <div>TUE</div>
                <div>WED</div>
                <div>THU</div>
                <div>FRI</div>
                <div>SAT</div>
            </div>

            <div class="days">
                <?php
        for ($i = 0; $i < 42; $i++) {
            $days = strtotime("+$i day", $tableFirstDay);
            $color = (date('m', $days) !== $month) ? 'color:lightgray' : '';
            echo '<div class="day-item" style=' . $color . '>
                        ' . date('d', $days) . '</div>';
        }
    echo "</div>";
    ?>
                <script>
                // 監聽所有HTML，有發生 click 的事件
                document.addEventListener('click', function(event) {
                    // 點擊到.day-item的格子裡
                    const clickedCell = event.target.closest('.day-item');
                    // 如果點擊到.day-item的格子裡，並且 clickedCell 裡的文字不是空白
                    if (clickedCell && clickedCell.innerText.trim() !== "") {
                        // prompt 彈出小視窗，暫停程式，直到使用者輸入完畢或按取消
                        const todoText = prompt("請輸入怪獸代辦事項：")
                        if (todoText) {
                            // newNote 新增 <div></div> 元素
                            const newNote = document.createElement('div')
                            // newNote 新增 <div></div> 裡的文字
                            newNote.innerHTML = "👾 " + todoText;
                            newNote.className = 'monster-note';
                            // 把 newNote 例如<div>👾 驚嚇課程 </div>，放在 clickedCell，appendChild是如果你同一個格子加兩次代辦事項，第二個會排在第一個下面，不會把第一個蓋掉
                            clickedCell.appendChild(newNote)
                        }
                    }
                })
                </script>
            </div>
        </div>
    </div>
</body>

</html>