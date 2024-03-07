const estudiantes = ["jesus", "eugenio", "jonh", "josias","jorge", "jasmin",""]; 

const est = ["jesus", "eugenio", "jonh", "josias","jorge", "jasmin",""]; 
let ganadorIndex;

function getRandomIntInclusive(min, max) {
 return Math.floor(Math.random() * (max - min + 1)) + min;
}


function sortear(){
 const randomIndex = getRandomIntInclusive(0, estudiantes.length - 1);
 ganadorIndex = randomIndex;
 document.querySelector(".resultadoGanador").textContent = `${estudiantes[ganadorIndex]} es el ganador!`;
 
}

function lista(){
    document.getElementById("lista").innerHTML=est.toString();
}