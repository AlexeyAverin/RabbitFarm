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
            var stringsrt = 'str=rab&';
        }
        if ( str != "rab" ) {
            var stringsrt = 'str=' + str + '&';
        }

        divMenu.innerHTML= '<div>Добрый день, Вы точно хотите удалить запись<p><a href="index.php?' + stringsrt + 'id=' + id + '&action=del">Да</a><a href="index.php">Нет</a></p></div>';
    }


    if (event.target.id == "cmbcrtbre" ) {
        var couplingid = event.target.getAttribute('couplingid');
        var action = event.target.getAttribute('id');
        var date = new Date;  breedingDate = date.toISOString().split('T')[0]; // alert("Добрый день!!!" + ' ' + couplingid + ' ' + action);
        var parentDiv = $("parcrtbre"); //event.target.parentNode;
        var divWindow = document.createElement("div");
        parentDiv.appendChild(divWindow);

        divWindow.classList.add("window");

        var divMenu = document.createElement('div');
        parentDiv.appendChild(divMenu);
        divMenu.classList.add("menu");
        divMenu.innerHTML = '<form method="GET" action="index.php" enctype="application/x-www-form-urlncoded"><div>Добрый день, <br>если Вы хотите из данной случки создать окрол, тогда заполните поля и нажмите "Создать", если нет нажмите "Отменить"</div><div><input name="couplingid" value="' + couplingid + '" type="hidden"><input name="str" value="bre" type="hidden"><input type="hidden" value="crtbre" name="action"><label>Дата окрола: </label><input name="breedingdate" value="' + breedingDate + '" type="date"><br><label>Общее кол-во: </label><input max="99" min="0" name="breedingnumberall" value="0" type="number"><br><label>Кол-во живых: </label><input min="0" max="99" name="breedingnumberlive" value="0" type="number"></div><div><input type="submit" value="Создать"><input id="cancrtbre" type="button" value="Отменить"></form></div>'

    }

    // Удаляем форму создания из случки окрола вслучае нажатия кнопки "отмена"
    if (event.target.id == "cancrtbre" ) {
        $('parcrtbre').innerHTML = '';
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