const questions = <?php echo json_encode($questions); ?>;
let currentQuestion = 0;
let score = 0;

const questionEl = document.getElementById('question');
const optionsEl = document.getElementById('options');
const nextBtn = document.getElementById('next');
const resultEl = document.getElementById('result');

function loadQuestion() {
    const q = questions[currentQuestion];
    questionEl.innerHTML = `<h3 class="text-lg font-semibold">${q.question}</h3>`;
    optionsEl.innerHTML = `
        <button onclick="selectOption(1)" class="block w-full p-2 mb-2 bg-gray-100 rounded hover:bg-gray-200">${q.option1}</button>
        <button onclick="selectOption(2)" class="block w-full p-2 mb-2 bg-gray-100 rounded hover:bg-gray-200">${q.option2}</button>
        <button onclick="selectOption(3)" class="block w-full p-2 mb-2 bg-gray-100 rounded hover:bg-gray-200">${q.option3}</button>
        <button onclick="selectOption(4)" class="block w-full p-2 mb-2 bg-gray-100 rounded hover:bg-gray-200">${q.option4}</button>
    `;
    nextBtn.classList.add('hidden');
}

function selectOption(option) {
    const q = questions[currentQuestion];
    if (option == q.correct_option) score++;
    optionsEl.querySelectorAll('button').forEach(btn => btn.disabled = true);
    nextBtn.classList.remove('hidden');
}

nextBtn.addEventListener('click', () => {
    currentQuestion++;
    if (currentQuestion < questions.length) {
        loadQuestion();
    } else {
        questionEl.innerHTML = '';
        optionsEl.innerHTML = '';
        nextBtn.classList.add('hidden');
        resultEl.innerHTML = `
            <p class="text-xl">Your Score: ${score} / ${questions.length}</p>
            <button onclick="location.reload()" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">Retry</button>
        `;
        resultEl.classList.remove('hidden');
    }
});

loadQuestion();