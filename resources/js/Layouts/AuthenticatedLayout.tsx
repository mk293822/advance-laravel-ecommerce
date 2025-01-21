import ApplicationLogo from "@/Components/app/ApplicationLogo";
import Navbar from "@/Components/app/Navbar";
import Dropdown from "@/Components/core/Dropdown";
import NavLink from "@/Components/core/NavLink";
import ResponsiveNavLink from "@/Components/core/ResponsiveNavLink";
import { Link, usePage } from "@inertiajs/react";
import {PropsWithChildren, ReactNode, useEffect, useRef, useState} from "react";

export default function AuthenticatedLayout({
  header,
  children,
}: PropsWithChildren<{ header?: ReactNode }>) {
  const user = usePage().props.auth.user;
  const props = usePage().props;

  const [message, setMessage] = useState<any[]>([]);
  const timeOutRefs = useRef<{[key: number]: ReturnType<typeof setTimeout>}>({})

  useEffect(() => {
    if(props.success.message){
       const newMessage = {
        ...props.success,
        id: props.success.time
      }

    setMessage((prevMessages)=> [newMessage, ...prevMessages]);

    const timeOutId = setTimeout(()=>{
      setMessage((prevMessages)=>
        prevMessages.filter((msg)=> msg.id !== newMessage.id)
      );

      delete timeOutRefs.current[newMessage.id];
    }, 4000)

    timeOutRefs.current[newMessage.id] = timeOutId;
    }
  }, [props.success, props.error]);

  return (
    <div className="min-h-screen bg-gray-100 dark:bg-gray-900">
      <Navbar/>

      {props.error && (
        <div className='container mx-auto px-8 mt-8'>
          <div className="alert alert-error">
            {props.error}
          </div>
        </div>
      )}

      {message.length > 0 && (
        <div className='toast toast-top toast-end z-[1000] mt-14 h-[13rem] overflow-hidden'>
          {message.map((msg)=>(
            <div key={msg.id} className="alert text-center alert-success shadow-md shadow-emerald-700">
              <span>{msg.message}</span>
            </div>
          ))}
        </div>
      )}

      <main>{children}</main>
    </div>
  );
}
