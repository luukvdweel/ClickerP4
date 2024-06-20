var score = 0;
var coinCost = 50;
var DIT = 0;
var clickMultiplier = 1; // Initialize click multiplier
var coin2 = 10; // Initialize coin2 with a value

function buyCoins() {
  if (score >= coinCost) {
    score -= coinCost;
    DIT++;
    coinCost = Math.round(coinCost * 1.25);
    document.getElementById("coincost").innerHTML = coinCost;
    document.getElementById("score").innerHTML = score + "D";
    document.getElementById("DIT").innerHTML = DIT;
  }
}

function add() {
  score * clickMultiplier; // Increase score by the click multiplier
  document.getElementById("score").innerHTML = score + "D";
  sessionStorage.setItem("score", score);
}

setInterval(function () {
  score += DIT; // Adjust score increment with DIT and click multiplier
  document.getElementById("score").innerHTML = score + "D";
}, 1000);

function multiplier() {
  if (score >= coin2) {
    score -= coin2;
    clickMultiplier *= 2; 
    document.getElementById("score").innerHTML = score + "D";
  }
}

// upload images 
function uploadImage() {
  const fileInput = document.getElementById('imageUpload');
  const file = fileInput.files[0];
  const reader = new FileReader();

  reader.onload = function(e) {
      const logoImage = document.getElementById('logoImage');
      logoImage.src = e.target.result;
  }

  if (file) {
      reader.readAsDataURL(file);
  }
}
