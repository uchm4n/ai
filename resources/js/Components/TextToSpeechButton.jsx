import React, { useState } from 'react';
import axios from 'axios';

export default function TextToSpeechButton({ text, className = '' }) {
    const [isPlaying, setIsPlaying] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [audioElement, setAudioElement] = useState(null);
    
    const handleTextToSpeech = async () => {
        try {
            if (isPlaying) {
                if (audioElement) {
                    audioElement.pause();
                    audioElement.currentTime = 0;
                }
                setIsPlaying(false);
                return;
            }
            
            setIsLoading(true);
            
            const response = await axios.post(route('dashboard.text-to-speech'), {
                text: text // TODO: Not working yet
            }, {
                responseType: 'blob'
            });
            
            // Create audio blob and play it
            const blob = new Blob([response.data], { type: 'audio/mpeg' });
            const url = URL.createObjectURL(blob);
            const audio = new Audio(url);
            
            audio.onended = () => {
                setIsPlaying(false);
                URL.revokeObjectURL(url);
            };
            
            audio.onpause = () => {
                setIsPlaying(false);
            };
            
            setAudioElement(audio);
            audio.play();
            setIsPlaying(true);
            
        } catch (error) {
            console.error('Error with text-to-speech:', error);
        } finally {
            setIsLoading(false);
        }
    };
    
    return (
        <button
            onClick={handleTextToSpeech}
            disabled={isLoading}
            className={`inline-flex items-center p-2 rounded-md transition-colors duration-200 
                hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 ${className}`}
        >
            {isLoading ? (
                <svg className="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            ) : isPlaying ? (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                </svg>
            ) : (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                </svg>
            )}
            <span className="ml-2">
                {isPlaying ? 'Stop' : 'Listen'}
            </span>
        </button>
    );
}
