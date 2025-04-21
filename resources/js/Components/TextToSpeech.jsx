import React, { useState, useRef, useEffect } from 'react';
import axios from 'axios';

export default function TextToSpeech({ className = '' }) {
    const [isPlaying, setIsPlaying] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const audioRef = useRef(new Audio());
    const speechContainerRef = useRef(null);
    
    useEffect(() => {
        // Get the element with ID "speech"
        const speechElement = document.getElementById('speech');
        if (speechElement) {
            speechContainerRef.current = speechElement;
        }
        
        // Cleanup on unmount
        return () => {
            stopAudio();
        };
    }, []);
    
    const stopAudio = () => {
        const audio = audioRef.current;
        audio.pause();
        audio.currentTime = 0;
        setIsPlaying(false);
    };
    
    const handleTextToSpeech = async () => {
        if (isPlaying) {
            stopAudio();
            return;
        }
        
        const speechElement = document.getElementById('speech');
        if (!speechElement) {
            console.error("No text element found to speak");
            return;
        }
        
        setIsPlaying(true);
        setIsLoading(true);
        
        try {
            // Get text content from the speech element
            const fullText = speechElement.textContent;
            
            // Fetch audio from the API
            const response = await axios.post('/text-to-speech', { text: fullText }, {
                responseType: 'blob'
            });
            
            // Create URL for the audio blob
            const audioUrl = URL.createObjectURL(response.data);
            
            // Setup audio element
            const audio = audioRef.current;
            audio.src = audioUrl;
            
            audio.onended = () => {
                stopAudio();
            };
            
            audio.onerror = (error) => {
                console.error("Audio playback error:", error);
                stopAudio();
            };
            
            // Start playing
            audio.play().then(() => {
                setIsLoading(false);
            }).catch(error => {
                console.error("Error playing audio:", error);
                setIsLoading(false);
                setIsPlaying(false);
            });
        } catch (error) {
            console.error("Error fetching audio:", error);
            setIsLoading(false);
            setIsPlaying(false);
        }
    };
    
    return (
        <div className={`${className}`}>
            <button
                onClick={handleTextToSpeech}
                disabled={isLoading}
                className={`inline-flex items-center p-2 rounded-md transition-colors duration-200 
                    hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500`}
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
        </div>
    );
}
