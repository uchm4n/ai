export default function Die(props) {

    const bg = props.isHeld ? 'bg-blue-500' : 'bg-gray-500';

    return (
        <button onClick={props.fn} className={`${bg} hover:bg-gray-700 text-white font-bold rounded p-3`}>
            {props.value}
        </button>
    );
}
