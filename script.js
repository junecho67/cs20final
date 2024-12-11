// API key and base URL
const apiKey = 'hg4nsv85lppeoqqixy3tnlt3k8lj6o0c';
const searchFormEndpoint = 'https://api-staging.adoptapet.com/search/search_form';
const petSearchEndpoint = 'https://api-staging.adoptapet.com/search/pet_search';
const petDetailsEndpoint = 'https://api-staging.adoptapet.com/search/limited_pet_details';

// Dynamically populate breeds when species is selected
document.getElementById('species').addEventListener('change', async (event) => {
  const species = event.target.value;

  if (!species) {
    // Clear the breed dropdown if no species is selected
    document.getElementById('breed').innerHTML = '<option value="">Select...</option>';
    return;
  }

  try {
    const response = await fetch(
      `${searchFormEndpoint}?key=${apiKey}&v=3&output=json&species=${species}`
    );
    const data = await response.json();

    const breedDropdown = document.getElementById('breed');
    breedDropdown.innerHTML = '<option value="">Select...</option>'; // Reset options

    if (data.breed_id && data.breed_id.length > 0) {
      data.breed_id.forEach((breed) => {
        const option = document.createElement('option');
        option.value = breed.value;
        option.textContent = breed.label;
        breedDropdown.appendChild(option);
      });
    }
  } catch (error) {
    console.error('Error fetching breeds:', error);
    alert('Failed to load breeds. Please try again.');
  }
});

// Handle form submission
document.getElementById('matchmaking-form').addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent form submission

  const species = document.getElementById('species').value;
  const breed = document.getElementById('breed').value;
  const age = document.getElementById('age').value;
  const sex = document.getElementById('sex').value;
  const size = document.getElementById('size').value;
  const location = document.getElementById('location').value;

  const formData = {
    species,
    breed,
    age,
    sex,
    size,
    location,
  };

  try {
    // Save data to the server
    const saveResponse = await fetch('save_data.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
    });

    const saveResult = await saveResponse.text();
    console.log('Save Data Response:', saveResult);

    if (saveResponse.ok) {
      alert('Your preferences have been saved!');
    } else {
      console.error('Error saving data:', saveResult);
      alert('Failed to save your preferences. Please try again.');
    }

    // Fetch and display pet search results
    const params = new URLSearchParams({
      key: apiKey,
      v: 3,
      output: 'json',
      city_or_zip: location,
      geo_range: '50',
      species,
      breed_id: breed,
      age,
      sex,
      pet_size_range_id: size,
      start_number: 1,
      end_number: 10,
    });

    const response = await fetch(`${petSearchEndpoint}?${params}`);
    const data = await response.json();

    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';

    if (data.pets && data.pets.length > 0) {
      for (const pet of data.pets) {
        const petCard = document.createElement('div');
        petCard.className = 'result-card';

        // Fetch pet details URL for each pet
        const petDetailsResponse = await fetch(
          `${petDetailsEndpoint}?key=${apiKey}&v=3&output=json&pet_id=${pet.pet_id}`
        );
        const petDetailsData = await petDetailsResponse.json();
        const petDetailsURL = petDetailsData.pet.pet_details_url;

        petCard.innerHTML = `
          <img src="${pet.results_photo_url}" alt="${pet.pet_name}">
          <h3>${pet.pet_name}</h3>
          <p>${pet.primary_breed}${pet.secondary_breed ? ` / ${pet.secondary_breed}` : ''}</p>
          <p>${pet.age}, ${pet.size}</p>
          <p>${pet.addr_city}, ${pet.addr_state_code}</p>
          <a href="${petDetailsURL}" target="_blank" class="view-details-btn">View Details</a>
        `;
        resultsDiv.appendChild(petCard);
      }
    } else {
      resultsDiv.innerHTML = '<p>No pets found. Try changing your search criteria.</p>';
    }
  } catch (error) {
    console.error('Error fetching pets:', error);
    document.getElementById('results').innerHTML =
      '<p>There was an error fetching pets. Please try again later.</p>';
  }
});
