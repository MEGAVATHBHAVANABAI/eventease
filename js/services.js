const services = [
  {
    id: 1,
    name: "Mehendi Artist",
    image: "images/mehendi.jpg",
  },

  {
    id: 2,
    name: "Hair Stylist",
    image: "images/hairstyle.jpg",
  },

  {
    id: 3,
    name: "Makeup Artist",
    image: "images/makeup.jpg",
  },

  {
    id: 4,
    name: "Photographer",
    image: "images/photography.jpg",
  },

  {
    id: 5,
    name: "Saree Draping",
    image: "images/sareedraping.jpg",
  },

  {
    id: 6,
    name: "Wall Painting",
    image: "images/wallpainting.jpg",
  },

  {
    id: 7,
    name: "Flower Decoration",
    image: "images/decoration.jpg",
  },
];
const container = document.getElementById("serviceContainer");
function displayServices(data) {
  if (!container) {
    return;
  }
  container.innerHTML = "";
  if (data.length === 0) {
  container.innerHTML = `
  <div class="col-12">
  <div class="no-results-message">
  <h4>😔 No Services Found</h4>
  <p>
  Please try searching for another service.
  </p>
  </div>
  </div>
  `;
  return;
  }
  data.forEach((service) => {
    container.innerHTML += `
  <div class="col-lg-4 col-md-6 mb-4 service-item"
  <div class="card service-card h-100">
  <!-- Service Image -->
<img
src="${service.image}"
class="card-img-top"
alt="${service.name}"
loading="lazy">
<!-- Card Content -->
<div class="card-body d-flex flex-column">
<h5 class="card-title">
 ${service.name}
</h5>
<p class="card-text">
 Professional
${service.name}
 available for all events.
</p>
<!-- View Categories -->
<a
href="service_categories.php?id=${service.id}"
 class="btn btn-success w-100 mt-auto">
  View Categories →
  </a>
  </div>
  </div>
  </div>
  `;
  });
}
displayServices(services);
const searchInput = document.getElementById("search");
if (searchInput) {
searchInput.addEventListener("keyup", function () {
const value = this.value.toLowerCase().trim();
const filteredServices = services.filter(function (service) {
return service.name.toLowerCase().includes(value);
});
displayServices(filteredServices);
});
}