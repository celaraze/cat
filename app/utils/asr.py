import os
import tempfile

import whisper


def transcribe_wav(file: bytes):
    temp_directory = tempfile.gettempdir()
    wav_path = os.path.join(temp_directory, 'sebastian_temp_corpus.wav')
    with open(wav_path, "wb") as wav_file:
        wav_file.write(file)
    model = whisper.load_model('turbo')
    result = model.transcribe(wav_path)
    return result['text']
