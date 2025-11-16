let errors = 0;
let cardList = [
    "darkness",
    "double",
    "fairy",
    "fighting",
    "fire",
    "grass",
    "lightning",
    "metal",
    "psychic",
    "water"
];

let cardSet;
let board = [];
const rows = 4;
const columns = 5;

let cardOneSelected;
let cardTwoSelected;

window.onload = function() {
    shuffleCards();
    startGame();
}

function shuffleCards() {
    cardSet = cardList.concat(cardList);
    console.log.apply(cardSet);

    for (let i = 0; i < cardSet.length; i++) {
        let j = Math.floor(Math.random() * cardSet.length);

        let temp = cardSet[i];
        cardSet[i] = cardSet[j];
        cardSet[j] = temp;
    }
    console.log(cardSet);
}

function startGame() {
    for (let r = 0; r < rows; r++) {
        let row = [];
        for (let c = 0; c < columns; c++) {
            let cardImg = cardSet.pop();
            row.push(cardImg);

            let card = document.createElement("img");
            card.id = r.toString() + "-" + c.toString();
            card.src = cardImg + ".jpg";
            card.classList.add("card");
            card.addEventListener("click", selectCard);
            document.getElementById("board").append(card);
        }
        board.push(row);
    }
    console.log(board);
    setTimeout(hideCards, 1000);
}

function hideCards() {
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            let card = document.getElementById(r.toString() + "-" + c.toString());
            card.src = "back.jpg";
        }
    }
}

function selectCard() {
    if (this.src.includes("back")) {
        if (!cardOneSelected) {
            cardOneSelected = this;

            let coords = cardOneSelected.id.split("-");
            let r = parseInt(coords[0]);
            let c = parseInt(coords[1]);

            cardOneSelected.src = board[r][c] + ".jpg";
        } else if (!cardTwoSelected && this != cardOneSelected) {
            cardTwoSelected = this;

            let coords = cardTwoSelected.id.split("-");
            let r = parseInt(coords[0]);
            let c = parseInt(coords[1]);

            cardTwoSelected.src = board[r][c] + ".jpg";
            setTimeout(update, 1000)
        }
    }
}

function update() {
    if (cardOneSelected.src != cardTwoSelected.src) {
        cardOneSelected.src = "back.jpg";
        cardTwoSelected.src = "back.jpg";
        errors += 1;
        document.getElementById("errors").innerText = errors;
    }

    cardOneSelected = null;
    cardTwoSelected = null;
}