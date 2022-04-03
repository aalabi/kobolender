const bVn = document.getElementById('bvnID').style.display = 'none';
const nIn = document.getElementById('ninID').style.display = 'none';

function changeStatus(){
    const status = document.getElementById('bvn_nin');
    if (status.value == "bvn"){
        document.getElementById('bvnID').style.display = 'block';
        document.getElementById('ninID').style.display = 'none';
    }
    else{
        document.getElementById('ninID').style.display = 'block';
        document.getElementById('bvnID').style.display = 'none';
    }

}

const howMuch = document.getElementById('how_much').style.display = 'none';
function changeDirect(){
    const status = document.getElementById('direct');
    if (status.value == "yes"){
        document.getElementById('how_much').style.display = 'block';
    }
    else{
        document.getElementById('how_much').style.display = 'none';
    }
}


const myPrice = document.getElementById('price').style.display = 'none';
function changebb(){
    const status = document.getElementById('bb');
    if (status.value == "yes"){
        document.getElementById('price').style.display = 'block';
        //alert('good')
    }
    else{
        document.getElementById('price').style.display = 'none';
    }
}



const myBaba = document.getElementById('babaman').style.display = 'none';
function changebaba(){
    const status = document.getElementById('baba');
    if (status.value == "yes"){
        document.getElementById('babaman').style.display = 'block';
        //alert('good')
    }
    else{
        document.getElementById('babaman').style.display = 'none';
    }
}




 document.getElementById('mamaboy').style.display = 'none';
function changemama(){
    const status = document.getElementById('mama');
    if (status.value == "yes"){
        document.getElementById('mamaboy').style.display = 'block';
        //alert('good')
    }
    else{
        document.getElementById('mamaboy').style.display = 'none';
    }
}


