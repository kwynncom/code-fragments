// Piece data (r = rank, lower = stronger; m = movable; s = scout)
const PIECES = {
  'B': {name:"Bomb",   r:-1, m:false},
  'F': {name:"Flag",   r:-2, m:false},
  'S': {name:"Spy",    r:999, m:true},
  '9': {name:"Scout",  r:9,   m:true, s:true},
  '8': {name:"Miner",  r:8,   m:true},
  '7': {name:"Sergeant",r:7,   m:true},
  '6': {name:"Lieutenant",r:6, m:true},
  '5': {name:"Captain", r:5,   m:true},
  '4': {name:"Major",   r:4,   m:true},
  '3': {name:"Colonel", r:3,   m:true},
  '2': {name:"General", r:2,   m:true},
  '1': {name:"Marshal", r:1,   m:true}
};

const RED_SETUP = ["1","2","3","3","4","4","4","5","5","5","5","6","6","6","6","7","7","7","7","S","B","B","B","B","B","B","F","8","8","8","9","9","9","9","9","9","9","9","8","8"];
const BLUE_SETUP = ["9","9","9","9","9","9","9","9","8","8","B","B","B","B","B","B","F","8","8","8","5","6","6","6","6","7","7","7","7","S","1","2","3","3","4","4","4","5","5","5"];

const board = Array(100).fill(null);
const redGrave = [], blueGrave = [];
let selected = null;
let currentPlayer = "red";
let gameOver = false;

function idx(r, c) { return r * 10 + c; }
function isLake(r, c) { return (r === 4 || r === 5) && (c === 2 || c === 3 || c === 6 || c === 7); }

function placePieces() {
  let k = 0;
  for (let r = 0; r < 4; r++) {
    for (let c = 0; c < 10; c++) {
      if (!isLake(r, c)) board[idx(r, c)] = {c: "red", t: RED_SETUP[k++]};
    }
  }
  k = 0;
  for (let r = 6; r < 10; r++) {
    for (let c = 0; c < 10; c++) {
      if (!isLake(r, c)) board[idx(r, c)] = {c: "blue", t: BLUE_SETUP[k++]};
    }
  }
}

function pieceHTML(p) {
  if (!p) return "";
  let s = p.t;
  if (p.t === 'B') s = '💣';
  if (p.t === 'F') s = '⚑';
  if (p.t === 'S') s = '🕵️';
  return `<div class="piece ${p.c}">${s}</div>`;
}

function graveHTML(p) {
  let s = p.t;
  if (p.t === 'B') s = '💣';
  if (p.t === 'F') s = '⚑';
  if (p.t === 'S') s = '🕵️';
  return `<div class="grave-piece ${p.c}">${s}</div>`;
}

function render() {
  document.querySelectorAll(".cell").forEach(cell => {
    const i = +cell.dataset.i;
    const p = board[i];
    cell.innerHTML = p ? pieceHTML(p) : "";
    cell.className = "cell" + (isLake(Math.floor(i/10), i%10) ? " lake" : "");
    if (p) cell.classList.add(p.c);
    if (selected === i) cell.classList.add("selected");
  });
  document.getElementById("red-graveyard").innerHTML = redGrave.map(graveHTML).join("");
  document.getElementById("blue-graveyard").innerHTML = blueGrave.map(graveHTML).join("");
  if (!gameOver) {
    document.getElementById("status").textContent = currentPlayer === "red" ? "🔴" : "🔵";
  }
}

function scoutValidPath(from, to) {
  const fr = Math.floor(from/10), fc = from % 10;
  const tr = Math.floor(to/10),   tc = to   % 10;
  if (fr !== tr && fc !== tc) return false;
  const dr = Math.sign(tr - fr);
  const dc = Math.sign(tc - fc);
  let r = fr + dr, c = fc + dc;
  while (r !== tr || c !== tc) {
    if (isLake(r, c) || board[idx(r, c)]) return false;
    r += dr;
    c += dc;
  }
  return true;
}

function isValidMove(fromIdx, toIdx) {
  if (gameOver || isLake(Math.floor(toIdx/10), toIdx%10)) return false;
  const piece = board[fromIdx];
  if (!PIECES[piece.t].m) return false;
  const target = board[toIdx];
  if (target && target.c === piece.c) return false;
  if (PIECES[piece.t].s) return scoutValidPath(fromIdx, toIdx);
  return Math.abs(Math.floor(fromIdx/10) - Math.floor(toIdx/10)) +
         Math.abs(fromIdx%10 - toIdx%10) === 1;
}

function showPossibleMoves(from) {
  document.querySelectorAll(".cell").forEach(cell => {
    if (isValidMove(from, +cell.dataset.i)) {
      cell.classList.add("possible-move");
    }
  });
}

function clearHighlights() {
  document.querySelectorAll(".cell").forEach(c => c.classList.remove("possible-move"));
}

function resolveAttack(attacker, defender) {
  const a = PIECES[attacker.t];
  const d = PIECES[defender.t];

  // Miner defeats Bomb
  if (a.name === "Miner" && d.name === "Bomb") return "attacker_wins";

  // Spy defeats Marshal
  if (a.name === "Spy" && d.name === "Marshal") return "attacker_wins";

  // Normal rank comparison
  if (a.r < d.r) return "attacker_wins";
  if (a.r > d.r) return "defender_wins";
  return "both_die";
}

function performMove(from, to) {
  if (gameOver) return;
  const attacker = board[from];
  const defender = board[to];

  if (!defender) {
    board[to] = attacker;
    board[from] = null;
  } else {
    if (defender.t === "F") {
      board[to] = attacker;
      board[from] = null;
      gameOver = true;
      document.getElementById("status").textContent = attacker.c.toUpperCase() + " WINS!";
      clearHighlights();
      render();
      return;
    }

    const result = resolveAttack(attacker, defender);

    if (result === "attacker_wins") {
      board[to] = attacker;
      board[from] = null;
      (defender.c === "red" ? redGrave : blueGrave).push(defender);
    } else if (result === "defender_wins") {
      board[from] = null;
      (attacker.c === "red" ? redGrave : blueGrave).push(attacker);
    } else {
      board[from] = null;
      board[to] = null;
      (attacker.c === "red" ? redGrave : blueGrave).push(attacker);
      (defender.c === "red" ? redGrave : blueGrave).push(defender);
    }
  }

  selected = null;
  clearHighlights();
  render();
  if (!gameOver) currentPlayer = currentPlayer === "red" ? "blue" : "red";
}

function initBoard() {
  const boardEl = document.getElementById("board");
  boardEl.innerHTML = "";
  for (let r = 0; r < 10; r++) {
    for (let c = 0; c < 10; c++) {
      const cell = document.createElement("div");
      cell.className = "cell" + (isLake(r, c) ? " lake" : "");
      cell.dataset.i = idx(r, c);
      cell.onclick = () => {
        const i = +cell.dataset.i;
        const piece = board[i];
        if (selected !== null) {
          if (i === selected) {
            selected = null;
            clearHighlights();
          } else if (piece && piece.c === currentPlayer) {
            selected = i;
            clearHighlights();
            showPossibleMoves(i);
          } else if (isValidMove(selected, i)) {
            performMove(selected, i);
          } else {
            selected = null;
            clearHighlights();
          }
        } else if (piece && piece.c === currentPlayer) {
          selected = i;
          showPossibleMoves(i);
        }
        render();
      };
      boardEl.appendChild(cell);
    }
  }
}

function startGame() {
  board.fill(null);
  redGrave.length = 0;
  blueGrave.length = 0;
  placePieces();
  initBoard();
  render();
}

startGame();