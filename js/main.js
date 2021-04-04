let tableData = '';
async function getTable() {
    await fetch('http://samgk.ru/gogks/?action=top100').then(data => data.json()).then(result => tableData = result)
}
getTable().then(res => {
    setTableData();
});

function viewGame(href){
	//http://samgk.ru/gogks/game.php
	let codeGame = '';
	async function getGame() {
		await fetch('http://samgk.ru/gogks/game.php?href='+href).then(res => res.text()).then(data => codeGame = data);
	}
	


	getGame().then(res => {
        if(codeGame == '') {
            alert('Данная игра недоступна для просмотра.');
        } else {
            let gp = document.querySelector('.gamepole');
            let gpParent = document.querySelector('#gamePole');
            gpParent.style.display = "flex";
            // gp.setAttribute('data-wgo', codeGame);
            var player = new WGo.BasicPlayer(gp, {
                sgf: codeGame,
                // move: 50
            });
        }

	});
}

let closeElement = document.querySelector('.close');
closeElement.addEventListener('click', function () {
    let gpParent = document.querySelector('#gamePole');
    gpParent.style.display = "none";
})

document.addEventListener('keydown', function (e) {
    let gpParent = document.querySelector('#gamePole');

    if(gpParent.style.display == "flex") {
        if(e.which == 27) {
            gpParent.style.display = "none";
        }
    }

})

function setTableData() {
    let table = document.getElementById('gameTable');
   let tbody = table.querySelector('tbody');
    for (let i = 0; i < tableData.length; i++) {
        
        if(tableData[i].games) {
            
            for(var numGame = 0; numGame < tableData[i].games.length; numGame++){
                var nameNumGame = numGame+1;
                console.log(tableData[i].games.length);
                let tr1 = document.createElement('tr');
                
                let td = document.createElement('td');
                    td.innerHTML = "Номер игры "+nameNumGame;
                    tr1.appendChild(td);

                for(key in tableData[i].games[numGame]) {
                    let td = document.createElement('td');
					if(key == 'gameHistory' && tableData[i].games[numGame][key] != 'Просмотр данной партии недоступен'){
						td.innerHTML = '<button class="viewGame custom-btn btn-12" data-href="'+tableData[i].games[numGame][key]+'" onClick="viewGame(\''+tableData[i].games[numGame][key]+'\');"><span>Окунись в мир Го</span><span>Просмотр игры</span></button>';
					} else {
						if(key == 'settigns' ){
							let td11 = document.createElement('td');
							td11.innerHTML = 'Загрузка...';
							tr1.appendChild(td11);
						}
						td.innerHTML = tableData[i].games[numGame][key];
					}
                    tr1.appendChild(td);
                }
                table.appendChild(tr1)
            }
        }

    }
    
	
	$(document).find(".viewGame").each(function(index, element){
		var parent = $(element).closest("tr").find("td").eq(7);
		var href = $(element).attr("data-href");
		
		$.ajax({
			url: 'http://samgk.ru/gogks/game.php?href='+href,
		}).done(function(response){
			if(response == ''){
				$(parent).text("Нет информации");
			} else {
				var regex = /TM\[(.*?)\]/gm;
				let m;

				while ((m = regex.exec(response)) !== null) {
					// This is necessary to avoid infinite loops with zero-width matches
					if (m.index === regex.lastIndex) {
						regex.lastIndex++;
					}
					
					// The result can be accessed through the `m`-variable.
					m.forEach((match, groupIndex) => {
						if(groupIndex == 1){
							if(match == 0){
								$(parent).text("Нет информации");
							} else {
								$(parent).text(match+" секунд");
							}
						}
					});
				} 
			}
		});
		
	});
	
	
}