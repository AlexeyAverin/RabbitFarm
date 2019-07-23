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

    if (event.target.id == "cmdcrtrab" ) {
        function fill_select( object, name ){
            var string = String( object );

            console.log('Добрый день!!!' + string)
            var array = string.split(',');
            var tag = '';
            for ( item in  array ) {
                tag += '<option>' + array[item] + '</option>';
            }
            tag = '<select name="' + name + '" id="' + name + '">' + tag + '</select>';
            return tag;
        }
      
    


        var breedingid = event.target.getAttribute('breedingid');
        var birth = event.target.getAttribute('birth');

        var parentDiv = $("parcrtrab");
        var divWindow = document.createElement("div");
        parentDiv.appendChild(divWindow);
        divWindow.classList.add("window");
        divMenu = document.createElement('div');
        parentDiv.appendChild(divMenu);
        divMenu.classList.add("menu");
        url = '/functor.php';
        params = 'metod=arrays_php_js';
  
        request = new XMLHttpRequest();
        request.responseType = 'json';
        request.open('POST', url, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        request.addEventListener('readystatechange', () => {
            if (request.readyState === 4 && request.status === 200 ) {
                let obj = request.response;

                console.log('Добрый день!!!' + 'Какие то параметры!!!' + obj.genders); //request.responseText
                divMenu.innerHTML = '<div>Добрый день, <br> если Вы хотите из данного окрола создать, учетную запись кролика, тогда заполните поля и нажмите "Создать", если нет нажмите "Отменить"!!!</div><div><label for="name">Кличка: </label><input pattern="[а-ЯА-Я0-9_]{3,34}" required id="name" name="name" type="text"><label for="breed">Порода: </label>' + fill_select(obj.breeds, 'breed') + '<br><label for="gender">Пол: </label>' + fill_select(obj.genders, 'gender') + '<label for="label">Метка: </label><input id="label" name="label" type="text"><br><label for="women">Крольчиха: </label>' + fill_select(obj.womens, 'women') + '<label for="men">Крол: </label>' + fill_select(obj.mens, 'men') + '<br><label for="injectiontype">Вакцина: </label>' + fill_select(obj.injections, 'injectiontype') + '<label for="injectiondate">Дата прививки: </label><input id="injectiondate" name="injectiondate" type="date"><br><input id="insrabsql" value="Создать" type="button"><input id="cancrtrab" value="Отмена" type="button"></div>';
            }
        });
        request.send(params);

        //divMenu.innerHTML = '<div>Добрый день!!!</div>';

    }

    if (event.target.id == "insrabsql" ) {
        $('parcrtrab').innerHTML = '';
        params = "url=www.rabbit.loc";
        request = new XMLHttpRequest();


        request.open('GET', 'index.php?str=rab&action=mod&rab=11', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.addEventListener('readystatechange', () => {
            if (request.readyState === 4 && request.status === 200 ) {
                console.log('Добрый день!!!' + 'Какие то параметры!!!' + request.responseText);
            }
        });

        request.send(params);

    }
    
    if (event.target.id == "cancrtrab" ) {
        $('parcrtrab').innerHTML = '';
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