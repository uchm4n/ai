import {Head, usePage} from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {useEffect, useRef, useState} from "react";
import { Transition} from "@headlessui/react";
import MouseTracker from "@/Pages/Tutorials/Partials/MouseTracker.jsx";
import FetchForm from "@/Pages/Tutorials/Partials/FetchForm.jsx";
import {Breadcrumb, Card, Col, Divider, FloatButton, Row, Space} from "antd";
import {PauseOutlined, PlayCircleOutlined, QuestionCircleOutlined, UpOutlined} from "@ant-design/icons";
import Breadcrumbs from "@/Components/Breadcrumbs";

export default function Tosty() {
    const title = 'Tosty';
    const [shown, setShown] = useState(false);
    const wrapperRef = useRef(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const audioRef = useRef(null);

    function handleKeyDown(e) {
        if (e.code === 'Space' || e.click === 'click') {
            setIsPlaying(prev => !prev);
        }
    }

    // hook for viewport intersection observer
    useEffect(() => {
        const abort = new AbortController();
        // if document's viewport intersects with the wrapperRef, setShown to true
        const observer = new IntersectionObserver((entries) => {
            const [entry] = entries;
            setShown(entry.isIntersecting);
        })

        observer.observe(wrapperRef.current);
        window.addEventListener('keydown', handleKeyDown);

        return () => {

            //  cleanup this event when component is unmounted
            abort.abort();

            // Old version
            // observer.disconnect();
            // window.removeEventListener('keydown', handleKeyDown);
        }
    }, []);

    // play or pause audio when isPlaying state changes
    useEffect(() => {
        if (isPlaying) {
            audioRef.current.play();
        } else {
            audioRef.current.pause();
        }
    }, [isPlaying]);

    function Clock() {
        const time = useCustomTime();
        return (
            <h2 className="text-gray-500">Time: {time.toDateString()} {time.toLocaleTimeString()}</h2>
        )
    }

    // custom hook to get current time
    function useCustomTime(){
        const [time, setTime] = useState(new Date());

        useEffect(() => {
            const interval = setInterval(() => {
                setTime(new Date());
            }, 1000);

            // cleanup interval when component is unmounted
            return () => clearInterval(interval);
        }, []);

        return time;
    }



    return (
        <AuthenticatedLayout header={<Breadcrumbs/>}>
            <Head title={title}/>

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">


                        <div className="p-6 flex flex-row">
                            <Clock/> <MouseTracker/>
                        </div>

                        <Divider></Divider>

                        <Row >
                            <Col span={24} className="p-6 pb-6 text-3xl text-center ">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit
                            </Col>
                            <Divider></Divider>

                            <Col span={12}>
                                <FetchForm/>
                            </Col>

                            <Row>
                                <Col className="p-6 pb-6 border">
                                    <QuestionCircleOutlined className=" text-red-500"/>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                    veniam?
                                    Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                    praesentium,
                                    quasi quis ratione recusandae repellendus similique sit tempore.
                                </Col>
                            </Row>
                            <Row>
                                <Col span={12} className="p-6 pb-6">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                    veniam?
                                    Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                    praesentium,
                                    quasi quis ratione recusandae repellendus similique sit tempore.
                                </Col>
                                <Col span={12} className="p-6 pb-6">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                    veniam?
                                    Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                    praesentium,
                                    quasi quis ratione recusandae repellendus similique sit tempore.
                                </Col>
                            </Row>

                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>
                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>
                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>
                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>

                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>
                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>

                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>
                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>

                            <div className="p-6 pb-6">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusantium ad adipisci assumenda cupiditate magnam placeat possimus ratione tempora
                                veniam?
                                Aliquam ducimus esse fugit modi natus numquam quod ratione repudiandae rerum.
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Autem culpa deleniti doloribus error ex explicabo facere illo iste minima mollitia nam
                                praesentium,
                                quasi quis ratione recusandae repellendus similique sit tempore.
                            </div>

                            <Divider
                                variant="dotted"
                                style={{
                                    borderColor: '#fd0000',
                                }}
                            >
                                End of the page
                            </Divider>


                            <div className="p-6 pb-6 ">
                                <audio ref={audioRef}>
                                    <source
                                        src="https://github.com/rafaelreis-hotmart/Audio-Sample-files/raw/master/sample.mp3"
                                        type="audio/mpeg"/>
                                </audio>

                                <Transition show={shown}>
                                    <div className="relative text-right w-50 transition duration-500 ease-in data-[closed]:opacity-0 ">

                                        <FloatButton
                                            onClick={() => setIsPlaying(prev => !prev)}
                                            onKeyDown={e => e.code === 'Space' && e.stopPropagation()}
                                            icon={isPlaying ? <PauseOutlined /> : <PlayCircleOutlined />}
                                            open={true}
                                            type="default"
                                            shape="square"
                                            style={{
                                                insetInlineEnd: "8%",
                                            }}
                                        />


                                        <FloatButton onClick={() => window.scrollTo(0, 0)}
                                            icon={<UpOutlined />}
                                            type="default"
                                            shape="square"
                                            style={{
                                                insetInlineEnd: "5%",
                                            }}
                                        />
                                    </div>
                                </Transition>
                            </div>

                            <div className="p-6 pb-6 text-center" ref={wrapperRef}>
                                <button className="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                                    Read more
                                </button>
                            </div>
                        </Row>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}