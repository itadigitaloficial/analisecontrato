document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('analysis-form');
    const feedback = document.getElementById('analysis-feedback');

    if (!form) {
        return;
    }

    const showFeedback = (message, type = 'success') => {
        feedback.textContent = message;
        feedback.classList.remove('hidden', 'success', 'error');
        feedback.classList.add(type);
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        feedback.classList.add('hidden');

        const formData = new FormData(form);
        try {
            const uploadResponse = await fetch('upload.php', {
                method: 'POST',
                body: formData
            });

            const uploadData = await uploadResponse.json();

            if (!uploadResponse.ok || !uploadData.file_url) {
                throw new Error(uploadData.error || 'Erro no upload.');
            }

            const payload = {
                nome: formData.get('nome'),
                whatsapp: formData.get('whatsapp'),
                file_url: uploadData.file_url
            };

            const webhookResponse = await fetch('https://n8n.itadigital.com.br/webhook/analise-contrato', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!webhookResponse.ok) {
                throw new Error('Erro ao enviar para análise.');
            }

            showFeedback('Contrato enviado com sucesso! Em breve você receberá o resultado.', 'success');
            form.reset();
        } catch (error) {
            showFeedback(error.message || 'Falha ao enviar contrato.', 'error');
        }
    });
});
