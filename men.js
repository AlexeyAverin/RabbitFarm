//Добрый день!!!






function $(id){
    return document.getElementById(id);
}


document.body.addEventListener("click", function(event){
    if (event.target.className == "erase-rabbit" ) {//alert('Добрый день!!!' + rabbitid);

        
        // Полупрозрачный фон
        var divWindow = document.createElement('div');
        event.target.appendChild(divWindow);
        divWindow.style.position = 'absolute';
        divWindow.style.top = '0px';
        divWindow.style.left = '0px';

        divWindow.style.width = '100vw';
        divWindow.style.height = '100vh';

        //divWindow.style.visibility = 'hidden';
        divWindow.style.backgroundColor = 'rgba(254, 254, 254, 0.7)';



        // Меню
        var divMenu = document.createElement('div');
        event.target.appendChild(divMenu);
        divMenu.style.position = 'absolute';
        divMenu.style.top = 'calc((100vh - 150px)/2)';
        divMenu.style.left = 'calc((100vw - 560px)/2)';
        divMenu.style.width = '500px';
        divMenu.style.height = '90px';

        divMenu.style.textAlign = 'center';
        divMenu.style.padding = '30px';
        divMenu.style.border = '1px solid orange';
        divMenu.style.borderRadius = '30px';
        divMenu.style.boxShadow = '10px 10px 10px rgb(230, 230, 230)';
        divMenu.style.backgroundColor = 'white';
        //divMenu.style.visibility = 'hidden';

        var rabbitid = event.target.getAttribute('rabbitid');
        divMenu.innerHTML= 'Добрый день, Вы точно хотите удалить запись<p><a href="index.php?rabbitid=' + rabbitid + '&action=del">Да</a><a href="index.php">Нет</a></p>';
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
            $('it').style.color = 'orange';
            // По индексу
            $('it').selectedIndex = 2;
            // По значению
            $('it').value = 'ABC';
        }
    }
});

function eraseRabit(){
    alert('Добрый день!');
}