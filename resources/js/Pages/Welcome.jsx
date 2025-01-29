import {Head, Link, usePage} from '@inertiajs/react';
import {Col, Divider, Row} from "antd";
import img from '/resources/img/1.gif';
import ApplicationLogo from "@/Components/ApplicationLogo.jsx";

export default function Welcome({auth, laravelVersion, phpVersion}) {
    const {appName, appEnv} = usePage().props;

    return (
        <>
            <Head title="Welcome"/>
            <Row>
                <Col span={24}>

                    <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

                        <div className="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                            <div className="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                                <Row>
                                    <Col span={24}>
                                        <header className="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                                            <div className="flex lg:col-start-2 lg:justify-center">
                                                <ApplicationLogo width={100} height={100} />
                                            </div>
                                            <nav className="-mx-3 flex flex-1 justify-end">
                                                {auth.user ? (
                                                    <Link
                                                        href={route('dashboard')}
                                                        className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                                    >
                                                        Dashboard
                                                    </Link>
                                                ) : (
                                                    <>
                                                        <Link
                                                            href={route('login')}
                                                            className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                                        >
                                                            Log in
                                                        </Link>
                                                        <Link
                                                            href={route('register')}
                                                            className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                                        >
                                                            Register
                                                        </Link>
                                                    </>
                                                )}
                                            </nav>
                                        </header>
                                    </Col>
                                </Row>

                                <Row className=" rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                                    <Col span={24} className="flex flex-col items-center ">
                                        <div className="w-2/3 p-3 sm:pt-5">
                                            <div className=" flex items-center gap-4">
                                                <h2 className="text-xl font-semibold  text-black dark:text-white">
                                                    {appName} Project
                                                </h2>
                                            </div>

                                            <p className="mt-4 text-lg">
                                                This is the demonstration of the project that utilizes LLM models (Phi4
                                                and DeepSeek) with predetermined parameters. Response is being streamed
                                                to the client in real-time.
                                            </p>
                                        </div>
                                        <Divider/>
                                        <div className="w-2/3 p-3 sm:pt-5">
                                            <h3 className="text-sm font-semibold text-black dark:text-white">Demo</h3>
                                            <img alt="" src={img}/>
                                        </div>
                                    </Col>
                                </Row>
                                <Row>
                                    <Col span={24}>
                                        <footer className="py-16 text-center text-sm text-black dark:text-white/70">
                                            {appEnv === 'local' && `Version: ${laravelVersion} (PHP v${phpVersion})`}
                                        </footer>
                                    </Col>
                                </Row>
                            </div>
                        </div>
                    </div>
                </Col>
            </Row>


        </>
    );
}
