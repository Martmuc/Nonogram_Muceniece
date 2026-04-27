//On initialise nos variables ici
//Dimensions de la Grille
let gridHeight = 0
let gridWidth = 0
//Timer et Score
var gameTime = 0
var score = 0
var scoreSaved = false
//Booléen qui gère si le Mode Indice est activé ou non
var hintOn = false
//Liste des musiques, jouables pendant le Jeu
var musics = window.musicsFromDatabase || []
var end = false
var currentGridId = -1

var selectedDifficulty = 0
let selectDifficulty = document.querySelectorAll(".difficulty")
        selectDifficulty.forEach(e => {
            e.addEventListener("click", function () {
                selectDifficulty.forEach(elt => {
                    elt.style.color = "limegreen"
                })
                e.style.color = "yellow"
                selectedDifficulty = parseInt(e.id.slice(10))
            })
        });

//Tableaux utiles à la création de la Grille
//Grille Solution
var gridSolution = []
//Grille de Jeu à remplir par le joueur
var grid = []
//Liste des Colonnes de la Grille Solution
var columnsSolution = []
//Listes des Indices des Lignes et Colonnes
var hintListRows = []
var hintListColumns = []
//Liste de toutes les possibilités de disposition des plages de Cases Remplies pour chaque Lignes et Colonnes
var allPossibility = []

/**
 * 
 * @param {*} grid Grille à copier
 * @returns Une copie de la Grille placée en paramètre
 */
function copy(grid) {
    res = [];
    for (let i = 0; i < grid.length; i++) {
        res[i] = [];
        for (let j = 0; j < grid[i].length; j++) {
            res[i][j] = grid[i][j];
        }
    }
    return res;
}

/**
 * Compare 2 Grilles pour déterminer leur égalité
 * @param {*} grid1 Un tableau de longueur gridHeight de tableau de longueur gridWidth, rempli d'entiers 
 * @param {*} grid2 Un tableau de même dimension et type que grid1
 * @returns Un booléen, true si les deux paramètres sont égaux, false sinon
 */
function compareGrid(grid1, grid2) {
    for (let i = 0; i < gridHeight; i++) {
        for (let j = 0; j < gridWidth; j++) {
            if (grid1[i][j] != grid2[i][j]) return false
        }
    }
    return true
}

/**
 * 
 * @param {*} row La Ligne ou Colonne pour laquelle on veut créer la liste d'indices des plages. 
 * @returns La liste d'indices des plages de la Ligne ou Colonne de la Grille Solution en paramètre. 
 */
function createHintList(row) {
    hintList = []
    indice = 0
    for (let i = 0; i < row.length; i++) {          // On traverse la Ligne / Colonne
        if (row[i] == 1) {                      // Si on y trouve une Case Remplie
            indice += 1                          // On incrémente la valeur de l'Indice
            if (i == row.length - 1) hintList.push(indice)                // Si on est au bout de la Ligne / Colonne, on ajoute l'Indice à la liste d'Indice de la Ligne / Colonne
        }
        else {                                // Sinon (Si on y trouve une Case Marquée)
            if (indice > 0) {                       // Si l'Indice est supérieur à 0
                hintList.push(indice)              // On ajoute l'Indice à la liste d'Indice de la Ligne / Colonne
                indice = 0                  // L'Indice devient 0 ( On se prépare à compter le prochain Indice de la Ligne / Colonne)
            }
        }
    }
    if (hintList.length == 0) hintList.push(0) // Si la liste d'Indice est vide, on lui ajoute un 0 (indiquant d'il n'y a que des Croix sur la Ligne / Colonne)
    return hintList
}

/**
 * 
 * @param {*} hintList La liste d'indice d'une Ligne ou Colonne
 * @param {*} possibilityLength La taille de la Ligne ou Colonne associée à hintList
 * @returns Une liste de toutes les possibilités de placelment de Cases Remplies en fonction d'une liste d'Indice, liste de liste, et de la taille de la Ligne / Colonne correspondant à cette liste d'Indices
 */
function allPossibilityList(hintList, possibilityLength) {
    res = []
    // Si l'indice est un 0 unique, la seule possibilité est une ligne de Croix, notées 2
    if (hintList == [0]) {
        possibility = []
        for (let i = 0; i < possibilityLength; i++) {
            possibility.push(2)
        }
        res.push(possibility)
        return res
    }
    // Sinon
    // On crée une liste des espaces de la Ligne / Colonne (Le nombre de Croix qui séparent deux Cases Remplies)
    // Ici on crée la Liste des espaces de la Possibilité 1 (Pas d'espace à gauche, autrement dit la Première Case de la Ligne / Colonne est Remplie, une seule Croix entre 2 groupes de Cases Remplies, que des Croix à droite de la dernière Case Remplie)
    emptyList = []
    sommeHintList = 0
    sommeEmptyList = 0
    for (let i = 0; i < hintList.length; i++) {
        if (i == 0) emptyList.push(0) // Pas d'espace à gauche du premier groupe de Cases Remplies
        else emptyList.push(1)      // Des espaces de taille 1 entre chaque groupe de Cases Remplies
        sommeHintList += hintList[i]
        sommeEmptyList += emptyList[i]
    }
    leftover = possibilityLength - sommeHintList - sommeEmptyList // Un espace de taille leftover (reste) à droite du dernier groupe de Cases Remplies
    emptyList.push(leftover)

    while (true) {
        //Création d'une Possibilité, une Configuration possible d'une Ligne / Colonne, déterminée en fonction de la liste d'Indices et de la taille de la Ligne / Colonne
        possibility = []
        for (let i = 0; i < emptyList.length; i++) {
            for (let j = 0; j < emptyList[i]; j++) { // On ajoute les Croix de l'espace i à la Possibilité
                possibility.push(2)
            }
            if (i != emptyList.length - 1) {         // On a un groupe de Croix de plus que de groupe de Cases Remplies, on met cette condition pour ne pas déborder de la liste d'Indices
                for (let j = 0; j < hintList[i]; j++) { // On ajoute les Cases Remplies du groupe de Cases Remplies i à la Possibilité
                    possibility.push(1)
                }
            }
        }
        // On ajoute la Possibilité créée à la liste de Possibilités
        res.push(possibility)
        //Sortie de la boucle while quand l'espace de gauche devient aussi long que l'espace de droite original
        if (emptyList[0] == leftover) break

        //Modification de la liste d'espaces
        //Regarde le premier chiffre en partant de la droite qui est supérieur à son minimum autorisé (0 au bord, 1 au centre), ajouter 1 à gauche, mettre le chiffre à son minimum, mettre le reste à droite
        for (let i = emptyList.length - 1; i >= 0; i--) { //On traverse tous les espaces en partant du dernier
            if (i == emptyList.length - 1) {               //Si on regarde le dernier espace
                if (emptyList[i] > 0) {             //S'il est plus grand que 0
                    emptyList[i - 1] += 1             //L'espace à sa gauche augmente de 1
                    emptyList[i] -= 1               //Tandis qu'il réduit de 1
                    break                           //On arrête de traverser les espaces
                }
            }
            else {                                //Sinon (Si on regarde pas le dernier espace)
                if (emptyList[i] > 1) {                //S'il est plus grand que 1
                    emptyList[i - 1] += 1             //L'espace à sa gauche augmente de 1
                    emptyList[i] -= 1               //Tandis qu'il réduit de 1
                    emptyList[emptyList.length - 1] = emptyList[i] - 1 //Le dernier espace devient l'espace courrant - 1
                    emptyList[i] = 1                              //L'espace courrant devient 1
                    break                                         //On arrête de traverser les espaces
                }
            }
        }
    }
    return res
}

//(Boucle du Solver)
//Prendre une image de la grille
//Compare chaque chiffre d'une ligne à la grille, si 2 vire toutes les possibilités 1, si 0 change rien, si 1 vire toutes les possibilités 2 (reducePossibilities)
//Pour toutes les possibilités restantes, si 100% d'entre elles ont une valeur en commun, l'inscrire sur la grille (editGrid)
//Faire ça pour toutes les lignes et colonnes
//Comparer la grille à son image, si identique on a fini, vérifier que c'est bien la solution, ou alors en déduire qu'il y a plusieurs solutions

/**
 * Enlève les possibilité qui ne correspondent pas à l'état actuel du tableau, d'une ligne ou d'une colonne numéroté rowNb
 * @param {*} rowNb La position de la Ligne / Colonne dans le tableau allPossibility
 */
function reducePossibilities(rowNb) {
    let i = 0
    while (i < allPossibility[rowNb].length) { //Tant qu'on a pas traversé toutes les possibilités
        for (let j = 0; j < allPossibility[rowNb][i].length; j++) { //Pour chaque Case de la Possibilité
            // Si c'est une Ligne et que la Case j est différente de la Case Correspondante sur la Grille de Jeu et que cette case de La Grille de Jeu n'est pas un 0 OU Si c'est une Colonne et ...
            if ((rowNb < gridHeight && allPossibility[rowNb][i][j] != grid[rowNb][j] && grid[rowNb][j] != 0) || (rowNb >= gridHeight && allPossibility[rowNb][i][j] != grid[j][rowNb - gridHeight] && grid[j][rowNb - gridHeight] != 0)) {
                allPossibility[rowNb].splice(i, 1) //Alors cette Possibilité n'est pas possible et elle dégage
                i -= 1                       //Après ce pop, les Possibilités suivante ont toutes reculé d'un cran dans la liste, nous faisons alors de même
                break
            }
        }
        i += 1
    }
}

/**
 * Traverse les possibilités d'une ligne ou colonne, et édite la grille si un Carré ou une Croix est présent à la même position 100% du temps
 * @param {*} rowNb La position de la Ligne / Colonne dans le tableau allPossibility 
 */
function editGrid(rowNb) {
    if (rowNb < gridHeight) length = gridWidth //Si on regarde une Ligne
    else length = gridHeight                 //Sinon (Si on regarde une Colonne)
    let edit = true
    let comparaison = 0
    for (let j = 0; j < length; j++) { //j = Position qu'on regarde dans la Ligne / Colonne
        for (let i = 0; i < allPossibility[rowNb].length; i++) { //i = Possibilité regardée
            if (i == 0) comparaison = allPossibility[rowNb][i][j] // on garde en mémoire la veleur à la position j de la possibilité 0, valeur notée "comparaison"
            else if (allPossibility[rowNb][i][j] != comparaison) {     // si une possibilité i a une valeur différente à cette position
                edit = false                                     // alors on édite pas la Grille (Autrement dit, si 100% des possibilités on la même valeur en une position j, on inscrit cette valeur sur la Grille)
                break
            }
        }
        if (edit) {                                                //Si on édite
            if (rowNb < gridHeight) grid[rowNb][j] = comparaison //Si on regarde une Ligne
            else grid[j][rowNb - gridHeight] = comparaison       //Sinon (Si on regarde une Colonne)
        }
        edit = true
    }
}

/**
 * Résout le puzzle
 * @returns true lorsque la grille est résolue.
 */
function solve() {
    while (true) {
        gridImage = copy(grid)
        for (let i = 0; i < gridHeight + gridWidth; i++) {
            reducePossibilities(i)
            editGrid(i)
        }
        if (compareGrid(grid, gridImage)) break
    }
    return compareGrid(grid, gridSolution)
}

/**
 * 
 * @returns true lorsque la Grille de Jeu contient des Cases Remplies exactement et seulement aux mêmes endroits que dans la Grille solution
 */
function finPartie() {
    for (let i = 0; i < gridHeight; i++) {
        for (let j = 0; j < gridWidth; j++) {
            if ((gridSolution[i][j] == 1 && grid[i][j] != 1) || (gridSolution[i][j] != 1 && grid[i][j] == 1)) return false
        }
    }
    end = true
    return true
}

/**
 * Crée le HTML de la Grille
 */
function createHTMLGrid() {
    longestRowhintList = 0 // On va calculer la ligne qui a le plus d'indices

    for (let i = 0; i < hintListRows.length; i++) {
        longestRowhintList = (hintListRows[i].length > longestRowhintList) ? hintListRows[i].length : longestRowhintList
    }

    longestColumnhintList = 0 // On va calculer la Colonne qui a le plus d'indices

    for (let i = 0; i < hintListColumns.length; i++) {
        longestColumnhintList = (hintListColumns[i].length > longestColumnhintList) ? hintListColumns[i].length : longestColumnhintList
    }

    hauteurGrille = gridHeight + longestColumnhintList
    largeurGrille = gridWidth + longestRowhintList

    let header = document.getElementById('header')
    header.innerHTML = '<h1 id="debut" style="grid-column:2/3;">Le Picross de trop</h1>'
    header.innerHTML += `
    <div style="display:flex; justify-content:center; align-items:center; gap:8px;">
        <label for="selectSong" style="color:limegreen;font-size: 2rem;">♫</label>
        <select name="songs" id="selectSong" class="form-select" style="max-width:420px;"></select>
        <div id="musicControlsInGame"></div>
    </div>`
    let musicControls = document.getElementById('musicControls')
    let musicControlsInGame = document.getElementById('musicControlsInGame')

    if (musicControls && musicControlsInGame) {
    musicControlsInGame.appendChild(musicControls)
}
    let selectSong = document.getElementById('selectSong')
    for (let i = 0; i < musics.length; i++) {
        selectSong.innerHTML += '<option value="extras/' + musics[i] + '" id="extras/' + musics[i] + '">' + musics[i].slice(0, musics[i].length - 4) + '</option>'
    }
    selectSong.addEventListener("change", function () {
        music2.pause()
        music2.currentTime = 0
        music2.setAttribute('src', '/nonogram/extras/' + musics[selectSong.selectedIndex])
        music2.play()

    })

    let main = document.getElementById('main')
let gridTitle = "Grille aléatoire"

if (typeof listGridNames !== "undefined" && typeof currentGridId !== "undefined" && currentGridId >= 0) {
    gridTitle = listGridNames[currentGridId]
}

main.innerHTML = `<article id="pic">
                    <h1>${gridTitle}</h1>
                 </article>
                 <footer></footer>`
    let article = document.getElementById("pic")
    article.innerHTML += '<div id="gridContainer" style="grid-template-columns: repeat(' + largeurGrille + ',20px);grid-template-rows: repeat(' + hauteurGrille + ',20px)">'
    article.innerHTML += '<div id="flex0" style="display: flex;"></div>'
    let flex0 = document.getElementById('flex0')
    flex0.innerHTML += '<h1 id="fin">Bon jeu !</h1>'
    flex0.innerHTML += '<h1 id="timer">Timer : 00:00:00</h1>'

    article.innerHTML += '<div id="flex" style="display: flex;"></div>'
    let flex = document.getElementById('flex')
    flex.innerHTML += '<h1 id="hint">Hint : Off</h1>'
    flex.innerHTML += '<h1 id="new">New Grid</h1>'
    flex.innerHTML += '<h1 id="clear">Clear Grid </h1>'
    let grille = document.getElementById("gridContainer")
    grille.style.height = (20 * hauteurGrille) + 'px'
    grille.style.width = (20 * largeurGrille) + 'px'

    //Partie supérieure, contenant les Indices Colonnes
    for (let i = 0; i < hauteurGrille - gridHeight; i++) {
        for (let j = 0; j < largeurGrille; j++) {
            if (largeurGrille - gridWidth > j) {
                let p = document.createElement('div')
                p.setAttribute("class", "caseVide")
                p.setAttribute("id", "caseVide" + (j + largeurGrille * i))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                grille.appendChild(p)
            }
            else if (longestColumnhintList - hintListColumns[j - (largeurGrille - gridWidth)].length <= i) {
                let p = document.createElement('div')
                p.setAttribute("class", "caseIndice noselect column" + (j - longestRowhintList))
                p.setAttribute("id", "caseVide" + (j + largeurGrille * i))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                let pText = document.createTextNode(hintListColumns[j - (largeurGrille - gridWidth)][i - (longestColumnhintList - hintListColumns[j - (largeurGrille - gridWidth)].length)])
                p.appendChild(pText)
                grille.appendChild(p)
            }
            else {
                let p = document.createElement('div')
                p.setAttribute("class", "caseVide")
                p.setAttribute("id", "caseVide" + (j + largeurGrille * i))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                grille.appendChild(p)
            }

        }
    }
    for (let i = hauteurGrille - gridHeight; i < hauteurGrille; i++) {
        for (let j = 0; j < largeurGrille; j++) {
            if (j < (longestRowhintList - hintListRows[i - (hauteurGrille - gridHeight)].length)) {
                let p = document.createElement('div')
                p.setAttribute("class", "caseVide")
                p.setAttribute("id", "caseVide" + (j + largeurGrille * i))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                grille.appendChild(p)
            }
            else if (j < largeurGrille - gridWidth) {
                let p = document.createElement('div')
                p.setAttribute("class", "caseIndice noselect row" + (i - longestColumnhintList))
                p.setAttribute("id", "caseVide" + (j + largeurGrille * i))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                let pText = document.createTextNode(hintListRows[i - (hauteurGrille - gridHeight)][j - (longestRowhintList - hintListRows[i - (hauteurGrille - gridHeight)].length)])
                p.appendChild(pText)
                grille.appendChild(p)
            }
            else {
                let p = document.createElement('div')
                p.setAttribute("class", "caseGrille noselect")
                p.setAttribute("id", ((j + largeurGrille * i) - (longestColumnhintList * (largeurGrille - longestRowhintList) + ((i + 1) * longestRowhintList))))
                p.setAttribute("style", "grid-row: " + (i + 1) + ' / ' + (i + 2) + ';grid-column:' + (j + 1) + ' / ' + (j + 2) + ";")
                grille.appendChild(p)

            }
        }
    }

}

/**
 * Detecte s'il y a une erreur sur la Grille de Jeu (Croix sur une Case remplie dans la Solution
 *  ou Rempli sur une Case Marquée dans la Solution)
 * @returns true et les indices i j de la case si erreur il y a, false sinon
 */
function detectError() {
    for (let i = 0; i < gridHeight; i++) {
        for (let j = 0; j < gridWidth; j++) {
            if ((grid[i][j] == 1 && gridSolution[i][j] == 2) || (grid[i][j] == 2 && gridSolution[i][j] == 1)) return [true, i, j]
        }
    }
    return [false]
}

/**
 * Change la couleur (d'arrière plan) des Indices
 * rng entre ligne ou colonne ?
 */
function changeHintBackground(row, column, color) {
    let chgt = document.querySelectorAll(".row" + row)
    chgt.forEach(e => {
        e.style.backgroundColor = color
    });

}

/**
 * Appelé lorsqu'on appuie sur Play, crée une Grille à solution unique et son HTML
 */
function nouvelleGrille(idGrid) {
    let totalCasesRemplies
    //Génération d'une Grille à solution unique
    while (true) {

        if(idGrid == -1){ //Si on joue en mode aléatoire
        //Gérer le nombre de cases remplies en fonction de la difficulté
        do{
        totalCasesRemplies = 0
        gridSolution = []
        for (let i = 0; i < gridHeight; i++) {
            row = []
            for (let j = 0; j < gridWidth; j++) {
                let rng = Math.floor(Math.random() * 100) + 1 //On génère un nombre de 1 à 100
                if ((selectedDifficulty == 0 && rng >= 25) || (selectedDifficulty == 1 && rng >= 50) || (selectedDifficulty == 2 && rng >= 60)){
                    row.push(1) // Remplissage pseudo-aléatoire de la Ligne i de la Grille Solution de Cases Remplies ou Marquées
                    totalCasesRemplies +=1
                }
                else row.push(2)
            }
            gridSolution.push(row) // Affectation de la Ligne i à la Grille Solution
        }
        }while(!(selectedDifficulty == 0) && !(selectedDifficulty == 1 && (totalCasesRemplies/(gridHeight*gridWidth))*100 <= 50) && !(selectedDifficulty == 2 && (totalCasesRemplies/(gridHeight*gridWidth))*100 <= 40))
        //console.log((totalCasesRemplies/(gridHeight*gridWidth))*100)
        }
        else{ //On est sur une Grille Prédéfinie
            gridSolution = listGrid[idGrid]
            gridHeight = gridSolution.length
            gridWidth = gridSolution[0].length
        }

        //Initialisation de la Grille de Jeu, une liste de liste d'entier, la Grille qui sera remplie par le Solver afin de déterminer si les Indices de la Grille Solution mènent à une Solution Unique
        grid = []
        for (let i = 0; i < gridHeight; i++) {
            row = []
            for (let j = 0; j < gridWidth; j++) {
                row.push(0) //On rempli cette Grille de Jeu de Cases Vides
            }
            grid.push(row)
        }

        //liste des la Solution des Colonnes
        columnsSolution = []
        for (let i = 0; i < gridWidth; i++) {
            column = []
            for (let j = 0; j < gridHeight; j++) {
                column.push(gridSolution[j][i])
            }
            columnsSolution.push(column)
        }

        hintListRows = []
        for (let i = 0; i < gridHeight; i++) {
            hintListRows.push(createHintList(gridSolution[i]))
        }
        hintListColumns = []
        for (let i = 0; i < gridWidth; i++) {
            hintListColumns.push(createHintList(columnsSolution[i]))
        }

        //Création de toutes les Possibilités de toutes les Lignes puis de toutes les Colonnes
        allPossibility = []
        for (let i = 0; i < gridHeight; i++) {
            allPossibility.push(allPossibilityList(hintListRows[i], gridWidth))
        }
        for (let i = 0; i < gridWidth; i++) {
            allPossibility.push(allPossibilityList(hintListColumns[i], gridHeight))
        }
        if (solve()) break

    }

    // On a généré une Grille à solution unique, on reset la Grille de Jeu
    grid = []
    for (let i = 0; i < gridHeight; i++) {
        row = []
        for (let j = 0; j < gridWidth; j++) {
            row.push(0) //On rempli cette Grille de Jeu de Cases Vides
        }
        grid.push(row)
    }
    createHTMLGrid()
}

/**
 * Gère le Timer et le Score, Appelée toutes les secondes
 */
function timer() {
    gameTime++
    let hours = '' + (Math.floor(gameTime / 3600))
    if (hours.length < 2) hours = '0' + hours
    let minutes = '' + (Math.floor(gameTime / 60) % 60)
    if (minutes.length < 2) minutes = '0' + minutes
    let seconds = '' + (gameTime % 60)
    if (seconds.length < 2) seconds = '0' + seconds
    let timer = document.getElementById('timer')
    timer.innerHTML = 'Timer : ' + hours + ':' + minutes + ':' + seconds
    if (hintOn) score -= 5
    else score -= 2
    if (score < 0) score = 0
}

//Gère l'écran de sélection des dimensions de Grille, assure que la Hauteur et la Largeur sont comprises entre 5 et 25 inclus
let inputH = document.getElementById('height')
let inputW = document.getElementById('width')
inputH.addEventListener("change", function () {
    if (parseInt(inputH.value) < 5 || inputH.value == '') inputH.value = '5'
    else if (parseInt(inputH.value) > 25 || inputH.value == '') inputH.value = '25'
})
inputW.addEventListener("change", function () {
    if (parseInt(inputW.value) < 5 || inputW.value == '') inputW.value = '5'
    else if (parseInt(inputW.value) > 25 || inputW.value == '') inputW.value = '25'
})

function ajouterScoreEnMonnaie(score) {
    if (scoreSaved) return
    scoreSaved = true

    fetch('/nonogram/add_score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'score=' + encodeURIComponent(score)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Monnaie ajoutée :', score)
        } else {
            console.error(data.message)
        }
    })
    .catch(error => {
        console.error('Erreur ajout monnaie :', error)
    })
}

function startPlay(elt){
    //Si les champs de selection de dimensions sont remplis, on initialise le jeu, sinon rien du tout
    currentGridId = parseInt(elt.id.slice(4))

    if (parseInt(elt.id.slice(4)) == -1){
    gridHeight = parseInt(inputH.value)
    gridWidth = parseInt(inputW.value)
}

    // ✅ ICI → afficher les boutons musique
    let musicControls = document.getElementById('musicControls')
    if (musicControls) {
        musicControls.classList.remove('d-none')
}

//  Musique
let music2 = document.getElementById('music2')
music2.loop = true

if (!musics || musics.length === 0) {
    alert("Vous n'avez aucune musique achetée. Le jeu démarre sans musique.")
} else {
    let music = musics[Math.floor(Math.random() * musics.length)]

    music2.setAttribute('src', '/nonogram/extras/' + music)
    music2.play()

    // Ancien code qui provoquait une erreur :
    // let musicStart = document.getElementById(music2.getAttribute('src'))
    // musicStart.setAttribute('selected', 'selected')

    // Nouveau code sécurisé :
    let selectSong = document.getElementById('selectSong')
    if (selectSong) {
        selectSong.value = music2.getAttribute('src')
    }
}
        

        //On génère le HTML de la Grille
        console.log(parseInt(elt.id.slice(4)))
        nouvelleGrille(parseInt(elt.id.slice(4)))
        
        //On initialise le score, 10 fois le nombre de Cases de la Grille
        score = gridHeight * gridWidth * 10

        //On affiche le bon titre dans le champ de sélection de musique
        // let musicStart = document.getElementById(music2.getAttribute('src'))
        // musicStart.setAttribute('selected', 'selected')
        let selectSong = document.getElementById('selectSong')
        if (selectSong) {
        selectSong.value = music2.getAttribute('src')
}

        //On peut changer la couleur d'un Indice de plage en cliquant dessus, seulement "utile" pour le joueur afin de l'aider à savoir ce qu'il a déjà fait
        let casesIndices = document.querySelectorAll(".caseIndice")
        casesIndices.forEach(e => e.addEventListener("click", function () {
            if (e.style.color == 'black' || e.style.color == '') e.style.color = 'crimson'
            else e.style.color = 'black'
        }))

        //Change l'état des Cases de la Grille quand on clique dessus, et termine la partie si toutes les Cases à Remplir sont Remplies
        let casesGrille = document.querySelectorAll(".caseGrille")
        casesGrille.forEach(e => e.addEventListener("click", function () {
            if (!end){       
            //Si on clique sur une Case Vide, elle devient Remplie
            if ((e.style.backgroundColor == 'white' || e.style.backgroundColor == '') && e.innerHTML != 'X') e.style.backgroundColor = 'blue'
            //Sinon si on clique sur une Case Remplie, elle devient Marquée
            else if (e.style.backgroundColor == 'blue') {
                e.style.backgroundColor = 'white'
                e.innerHTML = 'X'
            }
            //Sinon on clique sur une Case Marquée, elle se Vide
            else if (e.innerHTML == 'X') {
                e.innerHTML = ''
            }
            //On met à jour l'état logique (non-graphique) de la Grille 
            grid[parseInt(parseInt(e.id) / gridWidth)][parseInt(e.id) % gridWidth] = (grid[parseInt(parseInt(e.id) / gridWidth)][parseInt(e.id) % gridWidth] + 1) % 3

            if (finPartie()) {
                h.remove()
                clearGrid.remove()

                let fin = document.getElementById('fin')
                fin.innerText = "GG"

                clearInterval(monTimer)

                flex0.innerHTML += '<h1 id="score">Monnaie gagnée : ' + score + ' 🪙</h1>'

                ajouterScoreEnMonnaie(score)
            }
        }
        }))

        //On gère l'affichage du boutton Hint
        let h = document.getElementById('hint')
        hintOn = false
        h.addEventListener("click", function () {
            hintOn = !hintOn
            if (hintOn) h.innerHTML = 'Hint : On'
            else h.innerHTML = 'Hint : Off'
        })

        //À chaque clic, on gère l'affichage des Hints
        document.addEventListener('click', function () {
            //On reset la background-color de toutes les Cases Indices en blanc
            for (let i = 0; i < gridHeight; i++) {
                let elts = document.querySelectorAll(".row" + i)
                elts.forEach(e => {
                    e.style.backgroundColor = "white"
                });
            }
            for (let j = 0; j < gridWidth; j++) {
                let elts = document.querySelectorAll('.column' + j)
                elts.forEach(e => {
                    e.style.backgroundColor = "white"
                });
            }
            //Si le Mode Indice est activé
            if (hintOn) {
                //S'il y a au moins une erreur, on met le background-color des Cases Indices de la première Ligne où il y a une erreur en rouge
                let tab = detectError()
                if (tab[0]) {
                    changeHintBackground(tab[1], -1, 'red')
                }
                //Sinon, on calcule toutes les possibilités de palcement de Plages d'Indices
                else {

                    hintListRows = []
                    for (let i = 0; i < gridHeight; i++) {
                        hintListRows.push(createHintList(gridSolution[i]))
                    }
                    hintListColumns = []
                    for (let i = 0; i < gridWidth; i++) {
                        hintListColumns.push(createHintList(columnsSolution[i]))
                    }

                    //Création de toutes les Possibilités de toutes les Lignes puis de toutes les Colonnes
                    allPossibility = []
                    for (let i = 0; i < gridHeight; i++) {
                        allPossibility.push(allPossibilityList(hintListRows[i], gridWidth))
                    }
                    for (let i = 0; i < gridWidth; i++) {
                        allPossibility.push(allPossibilityList(hintListColumns[i], gridHeight))
                    }

                    for (let i = 0; i < gridHeight + gridWidth; i++) {
                        //On ne garde que les possibilités qui collent à la Grille de Jeu de la Ligne / Colonne i
                        reducePossibilities(i)

                        //On regarde si toutes les possibilités de cette Ligne / Colonnes ont un point commun

                        if (i < gridHeight) length = gridWidth //Si on regarde une Ligne
                        else length = gridHeight                 //Sinon (Si on regarde une Colonne)
                        let edit = true
                        let comparaison = 0
                        for (let j = 0; j < length; j++) { //j = Position qu'on regarde dans la Ligne / Colonne
                            for (let k = 0; k < allPossibility[i].length; k++) { //i = Possibilité regardée
                                if (k == 0) comparaison = allPossibility[i][k][j] // on garde en mémoire la veleur à la position j de la possibilité 0, valeur notée "comparaison"
                                else if (allPossibility[i][k][j] != comparaison) {     // si une possibilité i a une valeur différente à cette position
                                    edit = false                                     // alors on édite pas la Grille (Autrement dit, si 100% des possibilités on la même valeur en une position j, on inscrit cette valeur sur la Grille)
                                    break
                                }
                            }
                            rowNb = (i < gridHeight) ? i : i - gridHeight
                            //Si toutes les possibilités de cette Ligne / Colonnes ont un point commun et que ce point commun n'est pas déjà Placé sur la Grille de Jeu
                            //Alors on change le background-color dee la Ligne / Colonne en lime, indiquant qu'il y a un coup logique à jouer sur cette Ligne / Colonne
                            if (edit && (((i < gridHeight) && grid[rowNb][j] != allPossibility[i][0][j]) || (!(i < gridHeight) && grid[j][rowNb] != allPossibility[i][0][j]))) {                                if (i < gridHeight) {
                                    let elts = document.querySelectorAll(".row" + i)
                                    elts.forEach(e => {
                                        e.style.backgroundColor = "lime"

                                    });
                                }
                                else {
                                    let elts = document.querySelectorAll(".column" + (i - gridHeight))
                                    elts.forEach(e => {
                                        e.style.backgroundColor = "lime"

                                    });

                                }
                            }
                            edit = true
                        }
                    }
                }
            }
        })

        //Gère le boutton New Grid, qui recharge simplement la page
        let newGrid = document.getElementById('new')
        newGrid.addEventListener("click", function () {
            location.reload()
        })

        //Gère le boutton Clear Grid, qui rempli la Grille Logique de 0 et remet la Grille HTML blanche et vide
        let clearGrid = document.getElementById('clear')
        clearGrid.addEventListener('click', function () {
            let elts = document.querySelectorAll(".caseGrille")
            elts.forEach(e => {
                e.innerHTML = ''
                e.style.backgroundColor = 'white'
            });
            for (let i = 0; i < gridHeight; i++) {
                for (let j = 0; j < gridWidth; j++) {
                    grid[i][j] = 0
                }
            }
        })

        //Met à jour le Timer et le Score toute les secondes
        let monTimer = setInterval(timer, 1000)
    
}

let playFixed = document.getElementById('playPredef')

if (playFixed) {
    playFixed.addEventListener("click", function () {
        let main = document.getElementById('main')
        main.innerHTML = '<div id="flexPredef" style="display:flex; flex-wrap: wrap;"></div>'

        let flexPredef = document.getElementById('flexPredef')

        for (let i = 0; i < listGrid.length; i++) {
            flexPredef.innerHTML += `
                <article class="selectionGrille">
                    <h1>Grille n°${i + 1}</h1>
                    <h1 class="play" id="play${i}">Play</h1>
                </article>
            `
        }

        let play2 = document.querySelectorAll('.play')
        play2.forEach(elt => {
            elt.addEventListener("click", function () {
                startPlay(elt)
            })
        })
    })
}

//Gère tout le reste, ce qui ce passe après qu'on appuie sur Play
let play = document.querySelectorAll('.play')
play.forEach(elt => {
    elt.addEventListener("click", function () {
    startPlay(elt)
})
});

// document.addEventListener('DOMContentLoaded', function () {
//     if (window.selectedGridFromUrl !== null && window.selectedGridFromUrl !== undefined) {
//         startPlay({
//             id: 'play' + window.selectedGridFromUrl
//         })
//     }
// })

document.addEventListener('DOMContentLoaded', function () {
    console.log("selectedGridFromUrl =", window.selectedGridFromUrl);
    console.log("listGrid =", typeof listGrid !== "undefined" ? listGrid : "listGrid non chargé");

    if (window.selectedGridFromUrl !== null && window.selectedGridFromUrl !== undefined) {
        startPlay({
            id: 'play' + window.selectedGridFromUrl
        });
    }
});