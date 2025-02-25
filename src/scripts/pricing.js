document.addEventListener('DOMContentLoaded', () => {
  const data = [];
  const addButton = document.getElementById('add-button');
  const saveButton = document.getElementById('save-button');
  const cancelButton = document.getElementById('cancel-button');
  const inputForm = document.getElementById('input-form');
  const pricingTable = document.getElementById('pricing-tbody');
  const val1Input = document.getElementById('val1');
  const val2Input = document.getElementById('val2');
  const val3Input = document.getElementById('val3');

  let editingRow = null;

  addButton.addEventListener('click', () => {
    inputForm.classList.remove('hidden');
    addButton.classList.add('hidden');
  });

  saveButton.addEventListener('click', () => {
    const val1 = val1Input.value;
    const val2 = val2Input.value;
    const val3 = val3Input.value;

    if (editingRow) {
      const rowData = editingRow.dataset;
      data[rowData.index] = { col1: val1, col2: val2, col3: val3 };
      editingRow.children[0].textContent = val1;
      editingRow.children[1].textContent = val2;
      editingRow.children[2].textContent = val3;

      // Send the updated data to the server
      updateDatabase(rowData.index, val1, val2, val3);
      editingRow = null;
    } else {
      data.push({ col1: val1, col2: val2, col3: val3 });
      const rowIndex = data.length - 1;

      const row = document.createElement('tr');
      row.setAttribute('data-index', rowIndex);
      row.innerHTML = `
        <td>${val1}</td>
        <td>${val2}</td>
        <td>${val3}</td>
        <td>
          <button class="edit-button">Edit</button>
          <button class="delete-button">Delete</button>
        </td>
      `;
      pricingTable.appendChild(row);

      // Send the new data to the server
      addToDatabase(val1, val2, val3);
    }

    inputForm.classList.add('hidden');
    addButton.classList.remove('hidden');
    val1Input.value = '';
    val2Input.value = '';
    val3Input.value = '';
  });

  cancelButton.addEventListener('click', () => {
    inputForm.classList.add('hidden');
    addButton.classList.remove('hidden');
  });

  pricingTable.addEventListener('click', (event) => {
    if (event.target.classList.contains('edit-button')) {
      const row = event.target.closest('tr');
      const rowIndex = row.dataset.index;
      const rowData = data[rowIndex];

      val1Input.value = rowData.col1;
      val2Input.value = rowData.col2;
      val3Input.value = rowData.col3;

      inputForm.classList.remove('hidden');
      addButton.classList.add('hidden');
      editingRow = row;
    }

    if (event.target.classList.contains('delete-button')) {
      const row = event.target.closest('tr');
      const rowIndex = row.dataset.index;

      data.splice(rowIndex, 1);
      row.remove();
      updateIndices();

      // Send the delete request to the server
      deleteFromDatabase(rowIndex);
    }
  });

  function updateIndices() {
    const rows = pricingTable.querySelectorAll('tr');
    rows.forEach((row, index) => {
      row.setAttribute('data-index', index);
    });
  }

  function addToDatabase(val1, val2, val3) {
    fetch('add_test_price.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ name: val1, b2b: val2, b2c: val3 }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Data added successfully:', data);
    })
    .catch(error => {
      console.error('Error adding data:', error);
    });
  }

  function updateDatabase(index, val1, val2, val3) {
    fetch('update_test_price.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ index: index, name: val1, b2b: val2, b2c: val3 }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Data updated successfully:', data);
    })
    .catch(error => {
      console.error('Error updating data:', error);
    });
  }

  function deleteFromDatabase(index) {
    fetch('delete_test_price.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ index: index }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Data deleted successfully:', data);
    })
    .catch(error => {
      console.error('Error deleting data:', error);
    });
  }

  function confirmDelete() {
    return confirm("Are you sure you want to delete this test?");
  }
});

// document.addEventListener('DOMContentLoaded', () => {
//   const data = [];
//   const addButton = document.getElementById('add-button');
//   const saveButton = document.getElementById('save-button');
//   const cancelButton = document.getElementById('cancel-button');
//   const inputForm = document.getElementById('input-form');
//   const pricingTable = document.getElementById('pricing-tbody');
//   const val1Input = document.getElementById('val1');
//   const val2Input = document.getElementById('val2');
//   const val3Input = document.getElementById('val3');

//   let editingRow = null;

//   addButton.addEventListener('click', () => {
//     inputForm.classList.remove('hidden');
//     addButton.classList.add('hidden');
//   });

//   saveButton.addEventListener('click', () => {
//     const val1 = val1Input.value;
//     const val2 = val2Input.value;
//     const val3 = val3Input.value;

//     if (editingRow) {
//       const rowData = editingRow.dataset;
//       data[rowData.index] = { col1: val1, col2: val2, col3: val3 };
//       editingRow.children[0].textContent = val1;
//       editingRow.children[1].textContent = val2;
//       editingRow.children[2].textContent = val3;
//       editingRow = null;
//     } else {
//       data.push({ col1: val1, col2: val2, col3: val3 });
//       const rowIndex = data.length - 1;

//       const row = document.createElement('tr');
//       row.setAttribute('data-index', rowIndex);
//       row.innerHTML = `
//         <td>${val1}</td>
//         <td>${val2}</td>
//         <td>${val3}</td>
//         <td>
//           <button class="edit-button">Edit</button>
//           <button class="delete-button">Delete</button>
//         </td>
//       `;
//       pricingTable.appendChild(row);
//     }

//     inputForm.classList.add('hidden');
//     addButton.classList.remove('hidden');
//     val1Input.value = '';
//     val2Input.value = '';
//     val3Input.value = '';
//   });

//   cancelButton.addEventListener('click', () => {
//     inputForm.classList.add('hidden');
//     addButton.classList.remove('hidden');
//   });

//   pricingTable.addEventListener('click', (event) => {
//     if (event.target.classList.contains('edit-button')) {
//       const row = event.target.closest('tr');
//       const rowIndex = row.dataset.index;
//       const rowData = data[rowIndex];

//       val1Input.value = rowData.col1;
//       val2Input.value = rowData.col2;
//       val3Input.value = rowData.col3;

//       inputForm.classList.remove('hidden');
//       addButton.classList.add('hidden');
//       editingRow = row;
//     }

//     if (event.target.classList.contains('delete-button')) {
//       const row = event.target.closest('tr');
//       const rowIndex = row.dataset.index;
      
//       data.splice(rowIndex, 1);
//       row.remove();
//       updateIndices();
//     }
//   });

//   function updateIndices() {
//     const rows = pricingTable.querySelectorAll('tr');
//     rows.forEach((row, index) => {
//       row.setAttribute('data-index', index);
//     });
//   }

//   function confirmDelete() {
//     return confirm("Are you sure you want to delete this test?");
// }
// });


document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const tableRows = document.querySelectorAll("#pricing-tbody tr");

    function filterTests() {
        const query = searchInput.value.trim().toLowerCase();

        tableRows.forEach(row => {
            const testCode = row.cells[0].textContent.toLowerCase(); // Test Code column
            const testName = row.cells[1].textContent.toLowerCase(); // Test Name column

            if (testCode.includes(query) || testName.includes(query)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Search on button click
    searchButton.addEventListener("click", filterTests);

    // Search on "Enter" keypress
    searchInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            filterTests();
        }
    });

    // Search as the user types (Optional)
    searchInput.addEventListener("input", filterTests);
});
