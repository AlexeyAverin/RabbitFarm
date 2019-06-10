//Добрый день!!!






function $(id){
    return document.getElementById(id);
}


document.body.addEventListener("click", function(event){
    if (event.target.className == "erase-rabbit" ) {//alert('Добрый день!!!' + rabbitid);

        
        event.target.style.position = 'absolute';
        event.target.style.top = 'calc(50vh - 50px)';
        event.target.style.left = 'calc(50vw - 250px)';
        event.target.style.width = '500px';
        event.target.style.height = '90px';

        event.target.style.textAlign = 'center';
        event.target.style.padding = '30px';
        event.target.style.border = '1px solid orange';
        event.target.style.borderRadius = '30px';
        event.target.style.boxShadow = '10px 10px 10px rgb(230, 230, 230)';
        event.target.style.backgroundColor = 'white';
        //event.target.style.visibility = 'hidden';

        var rabbitid = event.target.getAttribute('rabbitid');
 

        event.target.innerHTML= 'Добрый день, Вы точно хотите удалить запись<p><a href="index.php?rabbitid=' + rabbitid + '&action=del">Да</a><a href="index.php">Нет</a></p>';

    }
});

function eraseRabit(){
    alert('Добрый день!');
}