<?php
require __DIR__ . '/vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Generator</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<style>
/* Основной стиль */
body {
    font-family: 'Roboto', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 100vh;
    margin: 0;
    padding: 30px 15px;
    background: linear-gradient(135deg, #ffe6e6, #e6f7ff, #fff0e6, #f5f5f5);
    background-size: 400% 400%;
    animation: gradientBG 20s ease infinite;
    color: #333;
    transition: background 0.5s;
    user-select: none;
}

h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    text-align: center;
    margin-bottom: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
}

#titleText {
    background: linear-gradient(90deg, #ff6a00, #ee0979, #ff6a00, #00c6ff, #ee0979);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    white-space: nowrap;
    overflow: hidden;
    animation: gradientText 5s ease infinite;
}

.cursor {
    display: inline-block;
    width: 1px;
    height: 1em;
    background: linear-gradient(90deg, #ff6a00, #ee0979, #00c6ff, #ff6a00);
    background-size: 300% 300%;
    margin-left: 2px;
    vertical-align: bottom;
    animation: gradientCursor 5s ease infinite;
}

@keyframes blink {0%,50%,100%{opacity:1;}25%,75%{opacity:0;}}
@keyframes gradientBG {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
@keyframes gradientText {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}
@keyframes gradientCursor {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    width: 100%;
    max-width: 360px; /* форма не растягивается на большие экраны */
    margin-bottom: 20px;
    padding: 0 10px;
}

input[type="text"] {
    padding: 12px 15px;
    font-size: 1rem;
    width: 100%;
    max-width: 320px; /* ограничение ширины поля на мобильных */
    border-radius: 6px;
    border: 2px solid #ccc;
    transition: 0.3s;
    outline: none;
}

input[type="text"]:focus {
    border-color: #ee0979;
    box-shadow: 0 0 8px rgba(238,9,121,0.4);
}

button, a.button {
    padding: 12px 20px;
    font-size: 1rem;
    background: linear-gradient(90deg, #ff6a00, #ee0979, #00c6ff);
    background-size: 300% 300%;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    animation: gradientButton 5s ease infinite;
}

button:hover, a.button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}

@keyframes gradientButton {0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.qr-display {
    max-width: 300px;
    width: 90%;
    padding: 15px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    background: #fff;
    opacity: 0;
    transform: scale(0.85);
    transition: all 0.5s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

.qr-display.show {opacity: 1; transform: scale(1);}
.qr-display svg {width: 100%; height: auto;}

#qrMessage {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 12px;
    background: linear-gradient(90deg, #ff6a00, #ee0979, #00c6ff);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: none;
    opacity: 0;
    animation: fadeIn 1s forwards, gradientText 5s ease infinite;
}

#downloadLink {
    display: none;
    margin-top: 10px;
    opacity: 0;
    width: 50%;
    max-width: 200px;
}

@keyframes fadeIn {from{opacity:0; transform:translateY(10px);} to{opacity:1; transform:translateY(0);}}

/* Адаптивность */
@media (max-width: 768px) {
    h1 {font-size: 1.7rem;}
    input[type="text"] {font-size: 0.95rem; max-width: 280px;}
    button, a.button {font-size: 0.95rem; padding: 10px 18px;}
}

@media (max-width: 480px) {
    h1 {font-size: 1.4rem; text-align: center;}
    input[type="text"] {font-size: 0.9rem; width: 90%; max-width: 250px;}
    button, a.button {font-size: 0.9rem; padding: 9px 16px;}
    .qr-display {max-width: 90%;}
    #downloadLink {width: 70%;}
}
</style>
</head>
<body>

<h1>
    <span id="titleText"></span><span class="cursor" id="cursor"></span>
</h1>

<form id="qrForm">
    <input type="text" name="link" placeholder="Вставьте сюда URL-адрес" required>
    <button type="submit">Сгенерировать QR</button>
</form>

<div class="qr-display" id="qrDisplay"></div>
<div id="qrMessage">Ваш QR готов!</div>
<a id="downloadLink" class="button" download="qr.svg">Скачать QR</a>

<script>
const text = "Генератор QR кодов";
const titleText = document.getElementById('titleText');
const cursor = document.getElementById('cursor');
const qrMessage = document.getElementById('qrMessage');
const downloadLink = document.getElementById('downloadLink');
let index = 0;

// Анимация заголовка
if(!sessionStorage.getItem('typed')) {
    function type() {
        if(index < text.length){
            titleText.textContent += text[index];
            index++;
            setTimeout(type, 150);
        } else {
            cursor.style.animation = 'blink 2s step-start infinite, gradientCursor 5s ease infinite';
            sessionStorage.setItem('typed', 'true');
        }
    }
    type();
} else {
    titleText.textContent = text;
    cursor.style.animation = 'blink 2s step-start infinite, gradientCursor 5s ease infinite';
}

// Генерация QR
const form = document.getElementById('qrForm');
form.addEventListener('submit', e => {
    e.preventDefault();
    const link = form.link.value.trim();
    if(link === '') return;

    fetch('generate.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'link=' + encodeURIComponent(link)
    })
    .then(response => response.text())
    .then(svg => {
        const qrDiv = document.getElementById('qrDisplay');
        qrDiv.innerHTML = svg;
        qrDiv.classList.add('show');

        qrMessage.style.display = 'block';
        qrMessage.style.opacity = 0;
        qrMessage.style.animation = 'fadeIn 0.8s forwards, gradientText 5s ease infinite';

        downloadLink.href = 'data:image/svg+xml;base64,' + btoa(svg);
        downloadLink.style.display = 'inline-block';
        downloadLink.style.animation = 'fadeIn 0.8s forwards 0.3s';
    });

    form.link.value = '';
});
</script>

</body>
</html>
