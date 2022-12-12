const message = 'Hello world' // Try edit me

// Update header text
document.querySelector('#header').innerHTML = message

var elements = document.querySelectorAll('input[label^="test"]');

for (let ndx = 0; ndx < elements.length; ndx++) {
  const item = elements[ndx];

  if (item.name.includes("2")) {
    item.value = 4;
  }
  
  console.log(item.value);
}