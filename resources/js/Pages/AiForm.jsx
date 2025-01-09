import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import {Textarea, Transition} from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { useRef } from 'react';

export default function AiForm() {
    const promptInput = useRef();

    const {
        data,
        setData,
        errors,
        post,
        processing,
        recentlySuccessful,
    } = useForm({
        promptInput: '',
    });

    const sendAi = (e) => {
        e.preventDefault();

        post(route('ai.send'), {
            preserveScroll: true,
            data: {
                prompt: promptInput,
            },
            onSuccess: (res) => {
                console.log(res);
            },
            onError: (errors) => {
                console.log(errors);
            },
        });
    };

    return (
        <section>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Ask AI
                </h2>

            </header>

            <form onSubmit={sendAi} className="mt-6 space-y-6">
                <div>
                    <InputLabel
                        htmlFor="prompt"
                        value="Prompt"
                    />

                    <Textarea
                        id="prompt"
                        ref={promptInput}
                        value={data.promptInput}
                        onChange={(e) =>
                            setData('promptInput', e.target.value)
                        }
                        type="text"
                        className="mt-1 block w-full"
                    />

                </div>



                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Save</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">
                            Saved.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}