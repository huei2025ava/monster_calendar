<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>怪獸電力公司萬年曆</title>

    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Varela+Round&display=swap" rel="stylesheet" />
    <!-- 終極專業版：用「檔案最後修改時間」當版本號 -->
    <!-- 優點：CSS 沒改就不重新下載、改了就全員立刻更新、永遠打敗快取、又省流量 -->
    <!-- 真正上線網站在用的寫法-->
    <link rel="stylesheet" href="style.css?t=<?php echo filemtime('style.css'); ?>">

</head>

<body>
    <?php
  date_default_timezone_set('Asia/Taipei');
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
                     $currentDate = date('Y-m-d', $days);
                     $dayNumber = date('d', $days);
                     $isToday = ($currentDate === date('Y-m-d')) ? 'today' : '';
                     $isOtherMonth = (date('m', $days) !== $month) ? 'color:lightgray' : '';
                     
                     echo "<div class=\"day-item $isToday\" data-date=\"$currentDate\" style=\"$isOtherMonth\">
                             $dayNumber
                           </div>";
                 }
                 ?>
                ?>
                <!-- 自訂彈出視窗 (新增待辦事項) -->
                <div id="custom-modal" class="modal-overlay">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">🎃 Add new task</h2>
                            <button class="modal-close" onclick="closeModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="modal-date"></p>
                            <input type="text" id="todo-input" class="modal-input" placeholder="輸入你的驚嚇任務..." />

                            <!-- 🆕 新增：顏色選擇區域 -->
                            <div class="color-picker-section">
                                <label class="color-label">color</label>
                                <div class="color-options">
                                    <button class="color-btn" data-color="mike-green"
                                        style="background: linear-gradient(135deg, #8cd147 0%, #4caf50 100%);"
                                        title="麥克綠"></button>

                                    <button class="color-btn" data-color="sulley-blue"
                                        style="background: linear-gradient(135deg, #6bc4e8 0%, #4a90e2 100%);"
                                        title="毛怪藍"></button>

                                    <button class="color-btn" data-color="sulley-purple"
                                        style="background: linear-gradient(135deg, #9b7be0 0%, #7e57c2 100%);"
                                        title="毛怪紫"></button>

                                    <button class="color-btn" data-color="mu-orange"
                                        style="background: linear-gradient(135deg, #ff9d42 0%, #ff7043 100%);"
                                        title="怪獸大學橘"></button>

                                    <button class="color-btn" data-color="mu-yellow"
                                        style="background: linear-gradient(135deg, #ffe24b 0%, #ffc107 100%);"
                                        title="怪獸大學黃"></button>

                                    <button class="color-btn" data-color="mu-pink"
                                        style="background: linear-gradient(135deg, #ff80ab 0%, #ff4081 100%);"
                                        title="怪獸大學粉"></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-btn cancel-btn" onclick="closeModal()">取消</button>
                            <button class="modal-btn confirm-btn" onclick="confirmTodo()">確認</button>
                        </div>
                    </div>
                </div>

                <!-- 🆕 新增：詳細視窗 (雙擊日期後顯示) -->
                <div id="detail-modal" class="modal-overlay">
                    <div class="modal-content detail-modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" id="detail-modal-title">📅 Todo list</h2>
                            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="modal-date" id="detail-modal-date"></p>

                            <!-- 待辦事項列表容器 -->
                            <div id="detail-todo-list" class="detail-todo-list">
                                <!-- 這裡會動態插入待辦事項 -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-btn confirm-btn" onclick="openAddFromDetail()">
                                ➕ 新增
                            </button>
                        </div>
                    </div>
                </div>
                <script>
                // ==================== 全域變數 ====================

                let weatherData = {};
                let todos = [];
                const STORAGE_KEY = 'monsterCalendarTodos_v22';
                let currentDateKey = '';
                let selectedColor = 'linear-gradient(135deg, #8cd147 0%, #4caf50 100%)';
                let fromDetailModal = false;
                let clickTimer = null;
                let clickCount = 0;
                let editingTodoId = null;
                let draggedTodoIndex = null;

                // ==================== DOM 載入完成後執行 ====================
                document.addEventListener('DOMContentLoaded', () => {
                    // ------- 抓天氣 -------
                    fetch('weather.php')
                        .then(r => r.json())
                        .then(data => {
                            console.log('氣象局資料抓到了！', data);
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(day => {
                                    weatherData[day.date] = {
                                        icon: day.icon,
                                        maxT: day.maxT
                                    };
                                });
                            } else {
                                applyBackupWeather();
                            }
                            renderWeather();
                        })
                        .catch(err => {
                            console.warn('抓天氣失敗，啟動備援', err);
                            applyBackupWeather();
                            renderWeather();
                        });

                    // ------- 備援天氣 -------
                    function applyBackupWeather() {
                        weatherData = {
                            '2025-12-02': {
                                icon: '多雲',
                                maxT: '25°C'
                            },
                            '2025-12-03': {
                                icon: '雨天',
                                maxT: '22°C'
                            },
                            '2025-12-04': {
                                icon: '雨天',
                                maxT: '19°C'
                            },
                            '2025-12-05': {
                                icon: '多雲',
                                maxT: '20°C'
                            },
                            '2025-12-06': {
                                icon: '多雲',
                                maxT: '23°C'
                            },
                            '2025-12-07': {
                                icon: '晴天',
                                maxT: '24°C'
                            },
                            '2025-12-08': {
                                icon: '多雲',
                                maxT: '23°C'
                            }
                        };
                    }

                    // ------- 圖示轉換 -------
                    function getCWAIcon(name) {
                        const map = {
                            '晴天': '100.png',
                            '多雲': '101.png',
                            '陰天': '102.png',
                            '雨天': '103.png',
                            '雷雨': '105.png'
                        };
                        return map[name] || '101.png';
                    }

                    // ------- 畫天氣 -------
                    function renderWeather() {
                        document.querySelectorAll('.day-item').forEach(cell => {
                            const date = cell.dataset.date;
                            if (!weatherData[date]) return;

                            let info = cell.querySelector('.weather-info');
                            if (!info) {
                                info = document.createElement('div');
                                info.className = 'weather-info';
                                cell.appendChild(info);
                            }

                            const w = weatherData[date];
                            info.innerHTML = `
                <div style="text-align:right; padding:2px 4px 0 0; line-height:1;">
                    <img src="./image/${getCWAIcon(w.icon)}" style="width:26px; height:26px; vertical-align:-7px;">
                    <span style="font-size:10px; opacity:0.8; margin-left:2px;">${w.maxT}</span>
                </div>`;
                        });
                    }

                    loadTodos();
                    renderTodos();
                    attachCalendarCellListeners();
                    attachColorButtonListeners();
                    attachDropListeners();
                });

                // ==================== 儲存 & 讀取資料 ====================
                function saveTodos() {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(todos));
                }

                function loadTodos() {
                    const data = localStorage.getItem(STORAGE_KEY);
                    if (data) {
                        todos = JSON.parse(data);
                    }
                }

                // ==================== 畫出所有待辦到日曆格子 ====================
                function renderTodos() {
                    // 先把舊的全部清掉
                    document.querySelectorAll('.monster-note').forEach(el => el.remove());

                    todos.forEach(todo => {
                        const cell = document.querySelector(`.day-item[data-date="${todo.date}"]`);
                        if (!cell) return;

                        const note = document.createElement('div');
                        note.className = 'monster-note';
                        note.style.setProperty('--note-color', todo.color);
                        note.dataset.id = todo.id;
                        note.draggable = true; // 可以拖曳

                        note.innerHTML = `
      <span class="drag-icon">👾&nbsp</span>
      <span class="todo-text">${todo.text}</span>
    `;

                        // 加上拖曳事件（跨日期移動）
                        note.addEventListener('dragstart', e => {
                            e.dataTransfer.setData('text/plain', todo.id);
                            note.classList.add('opacity-50');
                        });

                        note.addEventListener('dragend', () => {
                            note.classList.remove('opacity-50');
                        });

                        cell.appendChild(note);
                    });

                    // 重新綁定格子的 drop 事件（因為格子是固定的）
                    attachDropListeners();
                }

                // 讓格子可以被放下（跨日期拖曳）
                function attachDropListeners() {
                    document.querySelectorAll('.day-item').forEach(cell => {
                        cell.addEventListener('dragover', e => e.preventDefault());
                        cell.addEventListener('drop', e => {
                            e.preventDefault();
                            const todoId = parseInt(e.dataTransfer.getData('text/plain'));
                            const targetDate = cell.dataset.date;

                            if (!targetDate) return;

                            // 把這筆任務的日期改成新日期
                            todos = todos.map(todo => {
                                if (todo.id === todoId) {
                                    return {
                                        ...todo,
                                        date: targetDate
                                    };
                                }
                                return todo;
                            });

                            saveTodos();
                            renderTodos();
                            // 如果詳細視窗開著，也要立刻更新
                            if (document.getElementById('detail-modal').style.display === 'flex') {
                                renderDetailModal(currentDateKey);
                            }
                        });
                    });
                }

                // ==================== 單擊 / 雙擊日期格子 ====================
                function attachCalendarCellListeners() {
                    document.querySelectorAll('.day-item').forEach(cell => {
                        cell.addEventListener('click', function(e) {
                            // 如果點到的是待辦事項，就不要開視窗（讓它可以拖）
                            if (e.target.closest('.monster-note')) return;

                            const dateKey = this.dataset.date;
                            if (!dateKey) return;

                            clickCount++;

                            if (clickCount === 1) {
                                // 第一次點擊 → 等一下看有沒有第二下
                                clickTimer = setTimeout(() => {
                                    openModal(dateKey); // 單擊 → 開新增視窗
                                    clickCount = 0;
                                }, 300);
                            } else {
                                // 第二次點擊 → 雙擊
                                clearTimeout(clickTimer);
                                openDetailModal(dateKey); // 雙擊 → 開詳細視窗
                                clickCount = 0;
                            }
                        });
                    });
                }

                // ==================== 顏色選擇器 ====================
                function attachColorButtonListeners() {
                    document.querySelectorAll('.color-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            // 直接抓這個按鈕目前的背景（包含 linear-gradient）
                            selectedColor = window.getComputedStyle(this).backgroundImage;

                            // 選中樣式
                            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove(
                                'selected'));
                            this.classList.add('selected');
                        });
                    });

                    // 預設選第一個（怪獸大學綠）
                    document.querySelector('.color-btn')?.classList.add('selected');
                    selectedColor = window.getComputedStyle(document.querySelector('.color-btn')).backgroundImage;
                }

                // ==================== 新增視窗相關 ====================
                function openModal(dateKey) {
                    currentDateKey = dateKey;
                    fromDetailModal = false;

                    const modal = document.getElementById('custom-modal');
                    modal.querySelector('.modal-date').textContent = ` ${dateKey}`;
                    modal.style.display = 'flex';
                    document.getElementById('todo-input').value = '';
                    document.getElementById('todo-input').focus();
                }

                function closeModal() {
                    document.getElementById('custom-modal').style.display = 'none';
                }

                function confirmTodo() {
                    const text = document.getElementById('todo-input').value.trim();
                    if (!text || !currentDateKey) return;

                    const newTodo = {
                        id: Date.now(),
                        date: currentDateKey,
                        text: text,
                        color: selectedColor
                    };

                    todos.push(newTodo);
                    saveTodos();
                    renderTodos();

                    closeModal();

                    // 如果是從「詳細視窗」點進來的，要馬上回到詳細視窗
                    if (fromDetailModal) {
                        setTimeout(() => openDetailModal(currentDateKey), 100);
                        fromDetailModal = false;
                    }
                }

                // ==================== 詳細視窗相關（最強功能都在這）===================
                function openDetailModal(dateKey) {
                    currentDateKey = dateKey;
                    document.getElementById('detail-modal').style.display = 'flex';
                    document.getElementById('detail-modal-date').textContent = `${dateKey}`;
                    renderDetailModal(dateKey);
                }

                function closeDetailModal() {
                    document.getElementById('detail-modal').style.display = 'none';
                    editingTodoId = null;
                    draggedTodoIndex = null;
                }

                // 從詳細視窗點「新增」按鈕
                function openAddFromDetail() {
                    fromDetailModal = true;
                    closeDetailModal();
                    openModal(currentDateKey);
                }

                // 畫出詳細視窗裡的所有任務
                function renderDetailModal(dateKey) {
                    const dayTodos = todos.filter(t => t.date === dateKey);
                    const container = document.getElementById('detail-todo-list');

                    if (dayTodos.length === 0) {
                        container.innerHTML = '<div class="empty-state">這天沒有待辦事項喔～</div>';
                        return;
                    }

                    container.innerHTML = '';

                    dayTodos.forEach((todo, index) => {
                        const isEditing = editingTodoId === todo.id;

                        const item = document.createElement('div');
                        item.className = 'detail-todo-item';
                        item.dataset.id = todo.id;
                        item.draggable = !isEditing;

                        // 關鍵：直接用 CSS 變數存漸層
                        item.style.setProperty('--note-color', todo.color);

                        item.innerHTML = `
              <span class="todo-number">${index + 1}.</span>
              ${isEditing 
                ? `<input type="text" class="todo-edit-input" value="${todo.text}" data-id="${todo.id}" autofocus />`
                : `<span class="todo-text-editable" data-id="${todo.id}">${todo.text}</span>`
              }
              ${!isEditing ? `<button class="detail-delete-btn" data-id="${todo.id}">X</button>` : ''}
            `;

                        // 拖曳排序（詳細視窗內）
                        item.addEventListener('dragstart', e => {
                            draggedTodoIndex = index;
                            item.classList.add('opacity-50');
                        });

                        item.addEventListener('dragover', e => e.preventDefault());

                        item.addEventListener('drop', e => {
                            e.preventDefault();
                            if (draggedTodoIndex === null || draggedTodoIndex === index) return;

                            const [moved] = dayTodos.splice(draggedTodoIndex, 1);
                            dayTodos.splice(index, 0, moved);

                            const otherTodos = todos.filter(t => t.date !== dateKey);
                            todos = [...otherTodos, ...dayTodos];

                            saveTodos();
                            renderDetailModal(dateKey);
                            renderTodos();
                        });

                        item.addEventListener('dragend', () => {
                            item.classList.remove('opacity-50');
                            draggedTodoIndex = null;
                        });

                        container.appendChild(item);
                    });

                    attachDetailEvents();
                }

                function attachDetailEvents() {
                    // 點文字 → 進入編輯
                    document.querySelectorAll('.todo-text-editable').forEach(span => {
                        span.addEventListener('click', function() {
                            editingTodoId = parseInt(this.dataset.id);
                            renderDetailModal(currentDateKey);
                            setTimeout(() => {
                                const input = document.querySelector(
                                    `.todo-edit-input[data-id="${editingTodoId}"]`);
                                if (input) input.focus(), input.select();
                            }, 50);
                        });
                    });

                    // 編輯框失去焦點或按 Enter → 儲存
                    document.querySelectorAll('.todo-edit-input').forEach(input => {
                        const save = () => {
                            const newText = input.value.trim();
                            if (newText) {
                                todos = todos.map(t => t.id === parseInt(input.dataset.id) ? {
                                    ...t,
                                    text: newText
                                } : t);
                                saveTodos();
                                renderTodos();
                            }
                            editingTodoId = null;
                            renderDetailModal(currentDateKey);
                        };

                        input.addEventListener('blur', save);
                        input.addEventListener('keydown', e => {
                            if (e.key === 'Enter') save();
                            if (e.key === 'Escape') {
                                editingTodoId = null;
                                renderDetailModal(currentDateKey);
                            }
                        });
                    });

                    // 刪除按鈕
                    document.querySelectorAll('.detail-delete-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = parseInt(this.dataset.id);
                            todos = todos.filter(t => t.id !== id);
                            saveTodos();
                            renderTodos();
                            renderDetailModal(currentDateKey);
                        });
                    });
                }

                // ==================== 鍵盤快捷鍵 ===================
                document.addEventListener('keydown', e => {
                    const addModal = document.getElementById('custom-modal').style.display === 'flex';
                    const detailModal = document.getElementById('detail-modal').style.display === 'flex';

                    if (addModal && e.key === 'Enter') confirmTodo();
                    if (addModal && e.key === 'Escape') {
                        closeModal();
                        if (fromDetailModal) {
                            openDetailModal(currentDateKey);
                            fromDetailModal = false;
                        }
                    }
                    if (detailModal && e.key === 'Escape') closeDetailModal();
                });
                </script>
            </div>
        </div>
    </div>
</body>

</html>