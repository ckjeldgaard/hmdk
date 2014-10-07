function getWinner(winnerArray)
{
    var random = winnerArray[Math.floor(Math.random() * winnerArray.length)];
    alert("Den heldige vinder blev: " + random);
    return false;
}