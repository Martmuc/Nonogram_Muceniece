var gridHeight = 10
var gridWidth = 10



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
function compareGrid(grid1, grid2){
    for (let i = 0; i < gridHeight; i++){
        for (let j = 0; j < gridWidth; j++){
            if(grid1[i][j] != grid2[i][j]) return false
        }
    }
    return true
}

/**
 * 
 * @param {*} row La Ligne ou Colonne pour laquelle on veut créer la liste d'indices des plages. 
 * @returns La liste d'indices des plages de la Ligne ou Colonne de la Grille Solution en paramètre. 
 */
function createHintList(row){
    hintList = []
    indice = 0
    for (let i = 0; i < row.length; i++){          // On traverse la Ligne / Colonne
        if (row[i] == 1){                      // Si on y trouve une Case Remplie
            indice += 1                          // On incrémente la valeur de l'Indice
            if (i == row.length -1) hintList.push(indice)                // Si on est au bout de la Ligne / Colonne, on ajoute l'Indice à la liste d'Indice de la Ligne / Colonne
        }      
        else{                                // Sinon (Si on y trouve une Case Marquée)
            if (indice > 0){                       // Si l'Indice est supérieur à 0
                hintList.push(indice)              // On ajoute l'Indice à la liste d'Indice de la Ligne / Colonne
                indice = 0                  // L'Indice devient 0 ( On se prépare à compter le prochain Indice de la Ligne / Colonne)
            }
        }
    }                         
    if (hintList.length == 0) hintList.push(0) // Si la liste d'Indice est vide, on lui ajoute un 0 (indiquant d'il n'y a que des Croix sur la Ligne / Colonne)
    return hintList
}

/**
 * Affiche la Grille placée en paramètre avec les indices de plages qui y sont associés
 * @param {*} grid La Grille que l'on souhaite afficher
 */
function printGrid(grid){

    longestRowhintList = 0 // On va calculer la ligne qui a le plus d'indices

    for (let i = 0; i < hintListRows.length; i++){
        longestRowhintList = (hintListRows[i].length > longestRowhintList) ? hintListRows[i].length : longestRowhintList
    }
    strEspaceHint = '  '

    for (let i = 0; i < longestRowhintList * 2; i++){
        strEspaceHint += ' '
    }

    longestColumnhintList = 0 // On va calculer la Colonne qui a le plus d'indices

    for (let i = 0; i < hintListColumns.length; i++){
        longestColumnhintList = (hintListColumns[i].length > longestColumnhintList) ? hintListColumns[i].length : longestColumnhintList
    }
    //Affichage indices Colonnes
    let res = ''
    for (let i = 0; i < longestColumnhintList; i++){ 
        for (let j = 0; j < gridWidth; j++){
            if (longestColumnhintList - hintListColumns[j].length <= i) res = res + hintListColumns[j][i-(longestColumnhintList-hintListColumns[j].length)] + ' ' // Permet d'afficher proprement les indices
            else res = res + '  '
        }
        if (res.trim() != '') console.log(strEspaceHint + res)
        res = ''
    }
    splitHintGrid = ''
    for (let i = 0; i < gridWidth; i++){
        splitHintGrid += '- '
    }
    console.log(strEspaceHint + splitHintGrid)


    //Affichage Indices Lignes
    for (let i = 0; i < gridHeight; i++){
        strGrid = '' //=hint + les espaces qui faut.
        strHint = ''
        for (let j = 0; j < gridWidth; j++){
            if (j < hintListRows[i].length) strHint += hintListRows[i][j] + ' '
            strGrid += grid[i][j] + ' '
        }
        strEspaceHint = ''
        for (let j = 0; j < (longestRowhintList-hintListRows[i].length); j++){
            strEspaceHint += '  '
        }
        console.log(strEspaceHint + strHint + '| ' + strGrid)
    }
}

/**
 * 
 * @param {*} hintList La liste d'indice d'une Ligne ou Colonne
 * @param {*} possibilityLength La taille de la Ligne ou Colonne associée à hintList
 * @returns Une liste de toutes les possibilités de placelment de Cases Remplies en fonction d'une liste d'Indice, liste de liste, et de la taille de la Ligne / Colonne correspondant à cette liste d'Indices
 */
function allPossibilityList(hintList, possibilityLength){
    res = []
    // Si l'indice est un 0 unique, la seule possibilité est une ligne de Croix, notées 2
    if (hintList == [0]){
        possibility = []
        for (let i = 0; i < possibilityLength; i++){
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
    for (let i = 0; i < hintList.length; i++){
        if (i == 0) emptyList.push(0) // Pas d'espace à gauche du premier groupe de Cases Remplies
        else emptyList.push(1)      // Des espaces de taille 1 entre chaque groupe de Cases Remplies
        sommeHintList += hintList[i]
        sommeEmptyList += emptyList[i]
    }
    leftover = possibilityLength-sommeHintList-sommeEmptyList // Un espace de taille leftover (reste) à droite du dernier groupe de Cases Remplies
    emptyList.push(leftover)

    while (true){
        //Création d'une Possibilité, une Configuration possible d'une Ligne / Colonne, déterminée en fonction de la liste d'Indices et de la taille de la Ligne / Colonne
        possibility = []
        for (let i = 0; i < emptyList.length; i++){
            for (let j = 0; j < emptyList[i]; j++){ // On ajoute les Croix de l'espace i à la Possibilité
                possibility.push(2)
            }
            if (i != emptyList.length-1){         // On a un groupe de Croix de plus que de groupe de Cases Remplies, on met cette condition pour ne pas déborder de la liste d'Indices
                for (let j = 0; j < hintList[i]; j++){ // On ajoute les Cases Remplies du groupe de Cases Remplies i à la Possibilité
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
        for (let i = emptyList.length-1; i >= 0; i--){ //On traverse tous les espaces en partant du dernier
            if (i == emptyList.length-1){               //Si on regarde le dernier espace
                if (emptyList[i] > 0){             //S'il est plus grand que 0
                    emptyList[i-1] += 1             //L'espace à sa gauche augmente de 1
                    emptyList[i] -= 1               //Tandis qu'il réduit de 1
                    break                           //On arrête de traverser les espaces
                }
            }
            else{                                //Sinon (Si on regarde pas le dernier espace)
                if (emptyList[i] > 1){                //S'il est plus grand que 1
                    emptyList[i-1] += 1             //L'espace à sa gauche augmente de 1
                    emptyList[i] -= 1               //Tandis qu'il réduit de 1
                    emptyList[emptyList.length-1] = emptyList[i] -1 //Le dernier espace devient l'espace courrant - 1
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
function reducePossibilities(rowNb){
    let i = 0
    while (i <  allPossibility[rowNb].length){ //Tant qu'on a pas traversé toutes les possibilités
        for (let j = 0; j < allPossibility[rowNb][i].length; j++){ //Pour chaque Case de la Possibilité
            // Si c'est une Ligne et que la Case j est différente de la Case Correspondante sur la Grille de Jeu et que cette case de La Grille de Jeu n'est pas un 0 OU Si c'est une Colonne et ...
            if ((rowNb < gridHeight && allPossibility[rowNb][i][j] != grid[rowNb][j] && grid[rowNb][j] != 0) || (rowNb >= gridHeight && allPossibility[rowNb][i][j] != grid[j][rowNb-gridHeight] && grid[j][rowNb-gridHeight] != 0)){
                allPossibility[rowNb].splice(i,1) //Alors cette Possibilité n'est pas possible et elle dégage
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
function editGrid(rowNb){ 
    if (rowNb < gridHeight) length = gridWidth //Si on regarde une Ligne
    else length = gridHeight                 //Sinon (Si on regarde une Colonne)
    edit = true
    comparaison = 0
    for (let j = 0; j < length; j++){ //j = Position qu'on regarde dans la Ligne / Colonne
        for (let i = 0; i < allPossibility[rowNb].length; i++){ //i = Possibilité regardée
            if (i == 0) comparaison = allPossibility[rowNb][i][j] // on garde en mémoire la veleur à la position j de la possibilité 0, valeur notée "comparaison"
            else if (allPossibility[rowNb][i][j] != comparaison){     // si une possibilité i a une valeur différente à cette position
                edit = false                                     // alors on édite pas la Grille (Autrement dit, si 100% des possibilités on la même valeur en une position j, on inscrit cette valeur sur la Grille)
                break
            }
        }
        if (edit){                                                //Si on édite
            if (rowNb < gridHeight) grid[rowNb][j] = comparaison //Si on regarde une Ligne
            else grid[j][rowNb-gridHeight] = comparaison       //Sinon (Si on regarde une Colonne)
        }
        edit = true
    }
}

/**
 * Résout le puzzle
 * @returns true lorsque la grille est résolue.
 */
function solve(){
    while (true){
        //printGrid(grid)
        gridImage = copy(grid)
        for (let i = 0; i < gridHeight + gridWidth; i++){
            reducePossibilities(i)
            editGrid(i)
        }
        if (compareGrid(grid,gridImage)) break
    }
    return compareGrid(grid,gridSolution)
}

/**
 * 
 * @returns true lorsque la Grille de Jeu contient des Cases Remplies exactement et seulement aux mêmes endroits que dans la Grille solution
 */
function finPartie(){
    for (let i = 0; i < gridHeight; i++){
        for (let j = 0; j < gridWidth; j++){
            if ((gridSolution[i][j] == 1 && grid[i][j] != 1) || (gridSolution[i][j] != 1 && grid[i][j] == 1)) return false
        }
    }
    return true
}