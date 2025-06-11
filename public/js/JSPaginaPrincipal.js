document.addEventListener('DOMContentLoaded', () => {

  if (!sessionStorage.getItem('modalMostrado')) {
    document.getElementById('modal-preguntas').style.display = 'flex';
    sessionStorage.setItem('modalMostrado', 'true');
  } else {
    document.getElementById('modal-preguntas').style.display = 'none';
  }

  document.getElementById('btn-pregunta-1').onclick = () => {
    document.getElementById('pregunta-1').classList.add('hidden');
    document.getElementById('pregunta-3').classList.remove('hidden');
  };
  document.getElementById('btn-pregunta-2').onclick = () => {
    document.getElementById('pregunta-2').classList.add('hidden');
    document.getElementById('pregunta-4').classList.remove('hidden');
  };
  document.getElementById('btn-pregunta-3').onclick = () => {
    document.getElementById('pregunta-3').classList.add('hidden');
  };
  document.getElementById('btn-pregunta-4').onclick = () => {
    document.getElementById('pregunta-4').classList.add('hidden');
  };

  document.getElementById('cerrar-modal').onclick = () => {
    const p1 = document.getElementById('pregunta-1').classList.contains('hidden');
    const p2 = document.getElementById('pregunta-2').classList.contains('hidden');
    const p3 = document.getElementById('pregunta-3').classList.contains('hidden');
    const p4 = document.getElementById('pregunta-4').classList.contains('hidden');
    if (p1 && p2 && p3 && p4) {
      document.getElementById('modal-preguntas').style.display = 'none';
    } else {
      alert('Por favor, responde a todas las preguntas antes de continuar.');
    }
  };
});