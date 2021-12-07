const form = document.querySelector('form');
const nextBtn = document.querySelector('button#next');
const previousBtn = document.querySelector('button#previous');
const beginningBtnList = document.querySelectorAll('button.beginning');
const planCode = document.querySelector('input#code');
const quantity = document.querySelector('input#quantity');
const errorText = document.querySelector('p.error');
const beneficiariesSection = document.querySelector('section.beneficiaries');
const startBtn = document.querySelector('button#start');
const planBtn = document.querySelector('button.plan');
const plansSection = document.querySelector('section.plans');
const startSection = document.querySelector('section.start');
const modal = document.querySelector('#modal');
const closeModal = document.querySelector('span.close');
const planInformation = document.querySelector('div.plan-information');

const strToNumber = (field) => parseInt(field.value);

const showError = (message) => {
  errorText.innerHTML = message;
  errorText.classList.remove('hide');
}

const showNext = (element) => {
  element.parentElement.classList.add('hide');
  element.parentElement.nextElementSibling.classList.remove('hide');
}

const showPrevious = (element) => {
  element.parentElement.classList.add('hide');
  element.parentElement.previousElementSibling.classList.remove('hide');
}

const handlePlanInformation = async (plan) => {
  const info = await getPlanPrices(plan["codigo"]);
  modal.style = "display: block";
  planInformation.innerHTML = '';
  info.forEach(val => {
    const section = document.createElement('section');
    section.className = 'modal';
    section.appendChild(generateInformation(val["minimo_vidas"]));
    section.appendChild(generateInformation(`R$${val["faixa1"]}`));
    section.appendChild(generateInformation(`R$${val["faixa2"]}`));
    section.appendChild(generateInformation(`R$${val["faixa3"]}`));
    planInformation.appendChild(section);
  });
}

const generateInformation = (info) => {
  const p = document.createElement('p');
  const text = document.createTextNode(info);
  p.appendChild(text);
  return p;
}

const generateLabel = (index, type) => {
  const text = document.createTextNode(
    type === 'name' ? 
      `Entre com o nome do beneficiário ${index}:` : 
      `Entre com a idade do beneficiário ${index}:`
  );
  const label = document.createElement('label');
  label.htmlFor = `${type}-${index}`;
  label.appendChild(text);
  return label;
}

const generateInput = (index, type) => { 
  const input = document.createElement('input');
  input.type = type === 'name' ? 'text' : 'number';
  input.name = `${type}-${index}`;
  input.id = `${type}-${index}`;
  input.required = true;
  input.min = type === 'age' && 0;
  return input;
}

const generateInputWrapper = () => {
  const div = document.createElement('div');
  div.className = 'input-wrapper';
  return div;
}

const generateBeneficiaryInput = (index) => {
  const nameWrapper= generateInputWrapper();
  const ageWrapper= generateInputWrapper();
  const nameInput = generateInput(index, 'name');
  const nameLabel = generateLabel(index, 'name');
  const ageInput = generateInput(index, 'age');
  const ageLabel = generateLabel(index, 'age');
  nameWrapper.appendChild(nameLabel);
  nameWrapper.appendChild(nameInput);
  ageWrapper.appendChild(ageLabel);
  ageWrapper.appendChild(ageInput);
  beneficiariesSection.appendChild(nameWrapper);
  beneficiariesSection.appendChild(ageWrapper);
}

const generatePlanElement = (plan) => {
  const li = document.createElement('li');
  const name = document.createTextNode(plan["nome"]);
  li.appendChild(name);
  li.addEventListener('click', () => {
    handlePlanInformation(plan);
  });
  return li;
}

const getPlans = () => {
  return fetch('../data/planos.json')
    .then(response => response.json())
    .catch(error => console.log(error));
}

const getPlan = (id) => {
  return fetch('../data/planos.json')
    .then(response => response.json())
    .then(data => {
      const plan = data.find(plan => plan.codigo === id);
      return plan;
    })
    .catch(error => console.log(error));
}

getPlanPrices = (id) => {
  return fetch('../data/precos.json')
    .then(response => response.json())
    .then(data => {
      const prices = data.filter(plan => plan.codigo === id);
      return prices;
    })
    .catch(error => console.log(error));
}

const avoidSubmit = (e) => {
  if (e.keyCode === 13) {
    e.preventDefault();
  }
}

startBtn.addEventListener('click', () => {
  showNext(startBtn.parentElement);
});

nextBtn.addEventListener('click', async () => {
  errorText.classList.add('hide');
  const code = strToNumber(planCode);
  const quantityNumber = strToNumber(quantity);
  const plan = await getPlan(code);
  if (!plan) {
    showError('Por favor entre com um plano válido')
  } else if (!quantityNumber || quantityNumber <= 0 || quantityNumber > 10) {
    showError('Por favor entre com o número de beneficiários (1 a 10)')
  } else {
    beneficiariesSection.innerHTML = '';
    for (let i = 1; i <= quantityNumber; i++) {
      generateBeneficiaryInput(i);
    }
    showNext(nextBtn.parentElement);
  }
});

previousBtn.addEventListener('click', () => {
  showPrevious(previousBtn.parentElement);
});

beginningBtnList.forEach(beginningBtn => 
  beginningBtn.addEventListener('click', () => {
    window.location.replace('http://localhost:8181');
  })
);

planCode.addEventListener('keypress', avoidSubmit);

quantity.addEventListener('keypress', avoidSubmit);

planBtn.addEventListener('click', async () => {
  const plans = await getPlans();
  const plansList = plansSection.querySelector('ul');
  plansList.innerHTML = '';
  plans.forEach(plan => {
    plansList.appendChild(generatePlanElement(plan));
  });
  plansSection.appendChild(plansList);
  plansSection.classList.remove('hide');
  startSection.classList.add('hide');
});

closeModal.addEventListener('click', () => {
  modal.style = "display: none";
});

window.onclick = (e) => {
  if (e.target == modal) {
    modal.style = "display: none";
  }
};