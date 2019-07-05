//Добрый день!!!






function $(id){
    return document.getElementById(id);
}


document.body.addEventListener("click", function(event){
    if (event.target.className == "erase" ) {//alert('Добрый день!!!' + rabbitid);

        
        // Полупрозрачный фон
        var divWindow = document.createElement('div');
        event.target.appendChild(divWindow);
        divWindow.classList.add("window"); 


        // Меню
        var divMenu = document.createElement('div');
        event.target.appendChild(divMenu);
        divMenu.classList.add("menu");

        var id = event.target.getAttribute('id');
        var str = event.target.getAttribute('str');
        if ( str == "rab" || str == '' ) {
            var stringsrt = '';
        }
        if ( str != "rab" ) {
            var stringsrt = 'str=' + str + '&';
        }

        divMenu.innerHTML= '<div>Добрый день, Вы точно хотите удалить запись<p><a href="index.php?' + stringsrt + 'id=' + id + '&action=del">Да</a><a href="index.php">Нет</a></p></div>';
    }

    if (event.target.id == "crtbre" ) {
        var couplingid = event.target.getAttribute('couplingid');
        var action = event.target.getAttribute('id');
        var date = new Date;  breedingDate = date.toISOString().split('T')[0]; // alert("Добрый день!!!" + ' ' + couplingid + ' ' + action);
        var parentDiv = event.target.parentNode;
        var divWindow = document.createElement("div");
        parentDiv.appendChild(divWindow);
        divWindow.classList.add("window");

        var divMenu = document.createElement('div');
        parentDiv.appendChild(divMenu);
        divMenu.classList.add("menu");


        divMenu.innerHTML = '<div>Добрый день, <br>если Вы хотите из данной случки создать окрол, тогда заполните поля и нажмите "Создать", если нет нажмите "Отменить"</div><div><form method="GET" action="index.php" enctype="application/x-www-form-urlncoded"><input name="couplingId" value="' + couplingid + '" type="hidden"><input value="' + breedingDate + '" type="date"><input min="0" name="numberall" value="0" type="number"><input min="0" name="numberlive" value="0" type="number"></div><div><input type="submit" value="Создать"><input type="button" value="Отменить"></form></div>'

    }

});

// Две функции связанны для при изменении даты автоматически выбирался тип прививки
document.body.addEventListener("focusin", function(event){
    if ( event.target.id == "id" ) {// alert("Добрый день!"); console.log('Добрый день!!!' + event.target.value);
        event.target.setAttribute('old-value', event.target.value);
    }
});

// Две функции связанны для при изменении даты автоматически выбирался тип прививки
document.body.addEventListener("focusout", function(event){
    if ( event.target.id == "id" ) {// alert("Добрый день!");
        if ( event.target.value != event.target.getAttribute('old-value') ) { //console.log('Добрый день!!!' + ' <=> ');
            $('injectiontype').style.color = 'orange';
            // По индексу
            $('injectiontype').selectedIndex = 2;
            // По значению
            $('injectiontype').value = 'ABC';
        }
    }
});

function eraseRabit(){
    alert('Добрый день!');
}