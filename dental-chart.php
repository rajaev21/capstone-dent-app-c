<div class="my-4">
  <div class="card shadow p-3">
    <div class="card text-center">
      <h3 class="my-3">Dental Chart</h3>
      <div class="" id="legends">
        <h4>Legends</h4>
        <h6><span class="fw-bold">M</span> - missing</h6>
        <h6><span class="fw-bold">CO</span> - composite filling</h6>
        <h6><span class="fw-bold">AM</span> - amalgam</h6>
        <h6><span class="fw-bold">SP</span> - supernumerary</h6>
        <h6><span class="fw-bold">ER</span> - erupting tooth</h6>
        <h6><span class="fw-bold">GI</span> - glass ionomer</h6>
      </div>
      <div id="chart" class="mt-4 text-center"></div>
      <div id="saveButton" class="d-none btn btn-success m-2" onclick="saveChanges()">Save changes</div>
    </div>
  </div>

  <script>
    let legendChanges = []
    let shadeChanges = []
    let customerID = <?= $user ?>;

    window.addEventListener('DOMContentLoaded', async function() {
      const toothConditions = await fetchToothConditions();
      const toothArray = [
        [55, 54, 53, 52, 51, 61, 62, 63, 64, 65],
        [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28],
        [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38],
        [85, 84, 83, 82, 81, 71, 72, 73, 74, 75]
      ];

      const frontTooth = [53, 52, 51, 61, 62, 63, 13, 12, 11, 21, 22, 23, 43, 42, 41, 31, 32, 33, 83, 82, 81, 71, 72, 73, ]

      const chart = document.getElementById('chart');
      let html = "";

      toothArray.forEach((row, rowIndex) => {
        html += `<div class="row justify-content-center mb-3 g-2">`;

        row.forEach((tooth) => {
          const changeClass = frontTooth.includes(tooth) ? "front-tooth" : "back-tooth"
          const existingToothCondition = toothConditions.find(item => item.tooth === tooth)

          html += `
              <div class="col-auto">
                <div class="backtooth" style="display:inline-block;">
                  <label for="${tooth}" class="form-label" style="font-size:12px;">${tooth}</label>
                  <input
                    type="text"
                    class="form-control form-control-sm text-center mx-auto mb-2"
                    id="${tooth}"
                    value = "${existingToothCondition ? existingToothCondition.legend.toUpperCase() : ""}"
                    oninput="setLegend(this)"
                    style="width:40px; padding:0; font-size:12px; text-transform:uppercase;" />
                  <div class="${changeClass}">
                    <button class="btn btn-outline-primary" id="quad1" data-id="${tooth}" onclick="shade(this)"></button>
                    <button class="btn btn-outline-primary" id="quad2" data-id="${tooth}" onclick="shade(this)"></button>
                    <button class="btn btn-outline-primary" id="quad3" data-id="${tooth}" onclick="shade(this)"></button>
                    <button class="btn btn-outline-primary" id="quad4" data-id="${tooth}" onclick="shade(this)"></button>
                    ${changeClass === "back-tooth" ? `<button class="btn btn-outline-primary" id="quad5" data-id="${tooth}" onclick="shade(this)"></button>` : ``}
                  </div>
                </div>
              </div>
            `;
        });

        html += `</div>`;
      });

      chart.innerHTML = html;
      toothConditions.forEach(existingToothCondition => {
        const tooth = existingToothCondition.tooth;
        for (let i = 1; i <= 5; i++) {
          if (existingToothCondition[`quadrant${i}`] === 1) {
            const quad = document.querySelector(`#quad${i}[data-id="${tooth}"]`);
            if (quad) quad.style.backgroundColor = "gray";
          }
        }
      });
    });

    function setLegend(input) {
      const value = input.value.trim().toUpperCase()
      const id = input.id
      const existing = legendChanges.find(l => l.tooth === id);
      if (existing) {
        existing.value = value;
      } else {
        legendChanges.push({
          tooth: id,
          value
        });
      }
      const saveButton = document.getElementById('saveButton')
      saveButton.classList.remove('d-none')

      return
    }

    function shade(btn) {
      const isShaded = btn.style.backgroundColor === "gray" ? 0 : 1;
      btn.style.backgroundColor = isShaded === 0 ? "white" : "gray";
      let quadrant;

      const id = btn.dataset.id;
      if (btn.id === "quad1") {
        quadrant = "quadrant1"
      } else if (btn.id === "quad2") {
        quadrant = "quadrant2"
      } else if (btn.id === "quad3") {
        quadrant = "quadrant3"
      } else if (btn.id === "quad4") {
        quadrant = "quadrant4"
      } else if (btn.id === "quad5") {
        quadrant = "quadrant5"
      }
      const existing = shadeChanges.find(change => change.tooth === id && change.quadrant === quadrant);

      if (existing) {
        existing.value = isShaded
      } else {
        shadeChanges.push({
          tooth: id,
          value: isShaded,
          quadrant: quadrant
        });
      }

      const saveButton = document.getElementById('saveButton')
      saveButton.classList.remove('d-none')
    }

    async function fetchToothConditions() {
      const urlParams = new URLSearchParams(window.location.search);
      const aid = urlParams.get('aid');

      if (!aid) {
        console.warn("No AID found in URL");
        return [];
      }

      try {
        const res = await fetch(`http://localhost:5000/getFindings?aid=${aid}`);
        const data = await res.json();
        return data;
      } catch (err) {
        console.error("Failed to fetch tooth conditions:", err);
        return [];
      }
    }

    function saveChanges() {
      console.log(legendChanges)
      console.log(shadeChanges)

      const params = new URLSearchParams({
        legendChanges: JSON.stringify(legendChanges),
        shadeChanges: JSON.stringify(shadeChanges),
        user_id: customerID
      });

      const res = fetch(`http://localhost:5000/setToothConditionChanges?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
          if (data) {
            const saveButton = document.getElementById('saveButton')
            saveButton.classList.add('d-none')
          }
        })
        .catch(err => console.error(err));;
    }
  </script>