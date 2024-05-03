<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../../public/css/exp-cd-style.css" />
  <title>Flip App | Discover</title>
</head>
<body>
  <div class="container">
    <div class="panel active" style="background-image: url('../../public/img/app/discover/rob-hampson-cqFKhqv6Ong-unsplash.jpg')">
      <h3>Bienvenue sur @Flip App</h3>
    </div>
    <div class="panel" style="background-image: url('../../public/img/app/discover/annie-spratt-ITE_nXIDQ_A-unsplash.jpg')">
      <h3>Travailler plus efficacement...</h3>
    </div>
    <div class="panel" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1353&q=80')">
      <h3>Gamifier l'apprentissage</h3>
    </div>
    <div class="panel" style="background-image: url('https://images.unsplash.com/photo-1551009175-8a68da93d5f9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1351&q=80')">
      <h3>Discuter et échanger</h3>
    </div>
    <div class="panel" style="background-image: url('https://images.unsplash.com/photo-1549880338-65ddcdfd017b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80')">
      <h3>Des récompenses incroyables avec nos partenaires</h3>
      <a href="../">Allons-y</a>
    </div>
  </div>
  <script>
    const panels = document.querySelectorAll('.panel')
    panels.forEach(panel => {
        panel.addEventListener('click', () => {
            removeActiveClasses()
            panel.classList.add('active')
        })
    })

    function removeActiveClasses() {
        panels.forEach(panel => {
            panel.classList.remove('active')
        })
    }
  </script>
</body>
</html>
