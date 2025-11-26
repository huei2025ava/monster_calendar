<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>怪獸電力公司萬年曆</title>

    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Varela+Round&display=swap" rel="stylesheet" />

    <style>
    /* CSS 變數定義怪獸風格主色調 */
    :root {
        --sulley-blue: #33a2e5;
        /* 毛怪藍 */
        --mike-green: #90d344;
        /* 麥克綠 */
        --purple-patch: #855e97;
        /* 紫色斑點/裝飾 */
        --eye-white: #ffffff;
        /* 眼睛白 */
        --deep-shadow: #1e6091;
        /* 深藍陰影 (主要陰影色) */
        --light-border: rgba(0, 0, 0, 0.1);
        /* 輕微邊界色 */
    }

    body {
        font-family: "Varela Round", sans-serif;
        background: linear-gradient(135deg,
                var(--sulley-blue) 0%,
                var(--purple-patch) 100%);
        color: var(--deep-shadow);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .two-pane-container {
        display: flex;
        width: 1200px;
        max-width: 95%;
        background-color: var(--eye-white);
        border-radius: 40px;
        border: 8px solid var(--mike-green);
        box-shadow: 0 15px 30px var(--deep-shadow),
            0 0 0 15px var(--sulley-blue);
        overflow: hidden;
    }

    /* --- 左側小月曆樣式 --- */
    .mini-calendar-pane {
        width: 280px;
        padding: 30px 20px;
        background-color: var(--sulley-blue);
        color: var(--eye-white);
        border-right: 5px solid var(--mike-green);
        flex-shrink: 0;
        position: relative;
        /* 用於定位 TODAY 按鈕 */
    }

    .mini-calendar-pane h3 {
        font-family: "Bungee", cursive;
        font-size: 1.5rem;
        margin-top: 0;
        margin-bottom: 20px;
        text-align: center;
        color: var(--eye-white);
        text-shadow: 1px 1px 0 var(--deep-shadow);
    }

    .mini-calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    /* 小月曆導航圖片按鈕 */
    .mini-nav-arrow {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        filter: drop-shadow(2px 2px 0 var(--deep-shadow));
    }

    .mini-nav-arrow img {
        width: 30px;
        /* 圖片大小 */
        height: auto;
    }

    .mini-month-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--eye-white);
    }

    .mini-weekdays,
    .mini-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
        text-align: center;
    }

    .mini-weekdays {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        color: var(--eye-white);
        /* 星期文字白色 */
    }

    .mini-day-item {
        padding: 5px;
        font-size: 0.9rem;
        border-radius: 5px;
        transition: background-color 0.1s;
        color: var(--eye-white);
        /* 日期數字白色 */
    }

    .mini-day-item:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .mini-day-item.today {
        background-color: var(--mike-green);
        color: var(--deep-shadow);
        font-weight: 700;
    }

    /* TODAY 按鈕 (開門動畫 + 閃爍陰影) */
    .door-box {
        width: 282px;
        height: 282px;
        margin: 100px auto;
        position: relative;
        /* 移除 overflow: hidden; 讓陰影可以自由向外擴散，不被裁切。 */
        /* overflow: hidden; */
        background: transparent;
        cursor: pointer;
    }

    /* 門本體 */
    .door {
        width: 282px;
        height: 282px;
        background: url("./image/door.png") no-repeat;
        background-size: 3948px 282px;
        /* 14格總寬度 */
        image-rendering: pixelated;
        background-position: 0 0;
        transition: none !important;

        /* ==== 這裡是重點：閃爍陰影 ==== */
        /* 預設一直閃（非 hover 時） */
        animation: shadowFlicker 1.4s infinite alternate;

        /* 發光+陰影效果（可調整顏色跟強度） */
        filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.8)) drop-shadow(0 0 40px rgba(0, 255, 255, 0.6));
    }

    /* ==== 滑鼠移入時：停止閃爍 + 移除陰影（乾淨開門）==== */
    .door-box:hover .door {
        animation: openDoor 0.56s steps(13) forwards, shadowFlicker 0s paused;
        /* 強制暫停閃爍 */
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
        /* 開門時只留淡淡陰影 */
    }

    /* 滑鼠離開後恢復閃爍 + 倒播 */
    .door-box:not(:hover) .door {
        animation: closeDoor 0.56s steps(13) forwards,
            shadowFlicker 1.4s infinite alternate 0.56s;
        /* 倒播完 0.56s 後再開始閃 */
    }

    /* 正播 0→13 */
    @keyframes openDoor {
        from {
            background-position: 0px 0;
        }

        to {
            background-position: -3666px 0;
        }
    }

    /* 倒播 13→0 */
    @keyframes closeDoor {
        from {
            background-position: -3666px 0;
        }

        to {
            background-position: 0px 0;
        }
    }

    /* 閃爍陰影動畫 */
    @keyframes shadowFlicker {
        0% {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3)) drop-shadow(0 0 20px rgba(0, 204, 255, 0));
        }

        50% {
            filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.9)) drop-shadow(0 0 50px rgba(0, 255, 255, 0.8));
        }

        100% {
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5)) drop-shadow(0 0 35px rgba(0, 255, 204, 0.4));
        }
    }

    /* 新增：主月曆導航圖片按鈕的圖片尺寸 */
    .main-nav-arrow img {
        height: 60px;
        /* 固定圖片高度，避免過大 */
        width: auto;
        filter: drop-shadow(2px 2px 0 var(--deep-shadow));
    }

    /* 由於您沒有提供 .main-nav-arrow 的基礎樣式，為確保圖片在按鈕內置中，我們補上： */
    .main-nav-arrow {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        /* 圖片垂直置中 */
    }

    /* --- 右側大月曆樣式 --- */
    .main-calendar-pane {
        flex-grow: 1;
        padding: 30px;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin-bottom: 20px;
    }

    /* 標語樣式 */
    .slogan {
        font-family: "Bungee", cursive;
        font-size: 1.8rem;
        color: var(--purple-patch);
        letter-spacing: 5px;
        margin-bottom: 15px;
        text-shadow: 2px 2px 0 var(--mike-green);
    }

    /* --- 右側大月曆樣式 --- */

    /* 1. 使用 Grid 鎖定位置 */
    .month-nav-container {
        display: grid;
        /* 定義 6 欄，確保箭頭、月份和年份的位置都是固定的
       (1)左箭頭 (2)邊距 (3)月份 (4)年份 (5)邊距 (6)右箭頭 */
        grid-template-columns: 80px 1fr 180px 163px 1fr 68px;
        align-items: center;
        width: 100%;
        margin-bottom: 20px;
    }

    /* 2. 確保箭頭固定在 Grid 第 1 和 第 6 欄 */
    #prev-month-btn {
        grid-column: 1;
        /* 讓箭頭靠左 */
        justify-self: start;
    }

    #next-month-btn {
        grid-column: 6;
        /* 讓箭頭靠右 */
        justify-self: end;
    }

    /* 3. 月份和年份的基礎樣式 */
    .month-title {
        font-family: "Bungee", cursive;
        letter-spacing: 5px;
        margin: 0;
        white-space: nowrap;
    }

    /* 4. 鎖定 月份 的位置 */
    .month-title.month {
        font-size: 4rem;
        color: var(--mike-green);
        text-shadow: 3px 3px 0 var(--deep-shadow);
        /* 固定在 Grid 第 3 欄 */
        grid-column: 3;
        /* 讓月份文字在第 3 欄內置中 */
        justify-self: center;
    }

    /* 5. 鎖定 年份 的位置 */
    .month-title.year {
        font-size: 4rem;
        color: var(--purple-patch);
        text-shadow: 3px 3px 0 var(--deep-shadow);
        /* 固定在 Grid 第 4 欄 */
        grid-column: 5;
        /* 讓年份文字在第 4 欄內置中 */
        justify-self: center;
    }

    /* 由於箭頭、月份和年份都被明確定義了 Grid 欄位 (1, 3, 4, 6)，
   且欄位寬度 (80px, 1fr, 180px, 120px, 1fr, 80px) 也被鎖定，
   所以它們的位置將是固定且獨立的。 */

    /* 日期網格 */
    .weekdays,
    .days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: left;
    }

    .weekdays {
        border-bottom: 3px dashed var(--purple-patch);
        margin-bottom: 0;
        padding-bottom: 5px;
        /* 圖片中有間距 */
    }

    .weekday-item {
        font-family: "Bungee", cursive;
        color: var(--purple-patch);
        padding: 10px 10px 10px 5px;
        font-size: 1rem;
        font-weight: 400;
        border-right: 1px dotted var(--light-border);
        /* 點狀分隔線 */
    }

    .weekday-item:last-child {
        border-right: none;
    }

    /* --- 日期數字樣式：格子與邊界處理 --- */
    .day-item {
        height: 120px;
        border: 1px solid var(--light-border);
        border-top: none;
        /* 讓格子線條更簡潔 */
        border-left: none;
        padding: 5px;
        overflow: hidden;
        cursor: pointer;
        background-color: var(--eye-white);
        transition: background-color 0.1s;
    }

    .monster-note {
        font-size: 12px;
        background-color: var(--mike-green);
        color: var(--purple-patch);
        padding: 2px 5px;
        margin-top: 5px;
        border-radius: 4px;
        cursor: pointer;
    }

    .day-item:nth-child(7n) {
        border-right: none;
    }

    .day-item:hover {
        background-color: #f5f5f5;
    }

    .day-number {
        font-family: "Varela Round", sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--deep-shadow);
        margin-bottom: 5px;
    }

    .today .day-number {
        color: var(--eye-white);
        background-color: var(--purple-patch);
        display: inline-block;
        padding: 2px 8px;
        border-radius: 50%;
        box-shadow: 0 0 5px var(--mike-green);
    }

    /* 圖片中今天的日期有綠色光暈 */
    .day-item.today {
        box-shadow: 0 0 10px var(--mike-green) inset, 0 0 10px var(--mike-green);
    }

    /* 前一個月和下一個月的日期 */
    .day-item.prev-month .day-number,
    .day-item.next-month .day-number {
        color: rgba(0, 0, 0, 0.3);
        /* 灰色，減淡 */
    }

    /* 代辦事項/活動的風格 */
    .event {
        background-color: var(--mike-green);
        color: var(--eye-white);
        padding: 3px 5px;
        margin-top: 5px;
        /* 與日期數字有間隔 */
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        cursor: grab;
        box-shadow: 0 2px 0 var(--deep-shadow);
    }
    </style>
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